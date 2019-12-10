<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use ReflectionClass;
use ReflectionType;

class Transform
{
    /**
     * Transform raw array to structure desribed by type-hints from given class
     *
     * @param string $class Class to be filled in with data
     * @param array $data Data to map
     * @param string|null $fieldName Internal use only
     * @return mixed
     * @throws TransformException
     * @throws UnsupportedException
     */
    public function to(string $class, $data, ?string $fieldName = null)
    {
        if ($data === null) {
            throw new TransformException(
                $this->to(FieldError::class, ['field' => 'root', 'reason' => 'Data could not be decoded'])
            );
        }

        $errors = [];

        // Validation of scalars to handle potential recursive calls nicely
        if (in_array($class, ['string', 'int', 'float', 'bool'])) {
            try {
                $data = $this->checkScalar($class, $data, 'var');
            } catch (FieldError $e) {
                throw new TransformException($e);
            }
            return $data;
        }

        $objReflection = new ReflectionClass($class);
        $properties = $objReflection->getProperties();
        $defaults = $objReflection->getDefaultProperties();

        $finalData = [];
        foreach ($properties as $property) {
            if (! $property->isPublic()) {
                continue;
            }
            $name = $property->getName();

            // We ignore untyped properties but pass data "as they are"
            if (! $property->hasType()) {
                $finalData[$name] = $data;
                continue;
            }

            if (!array_key_exists($name, $data)) {
                if (!array_key_exists($name, $defaults)) {
                    // Declared property without default value does not exist in data
                    $errors[] = $this->to(FieldError::class, [
                        'field' => ($fieldName !== null ? $fieldName . '.' : '') . $name,
                        'reason' => 'Field must have value'
                    ]);
                } else {
                    // Declared property does not exists in data but has default, use it
                    $finalData[$name] = $defaults[$name];
                }
                continue;
            }

            try {
                $finalData[$name] = $this->checkType(
                    $property->getType(),
                    $data[$name],
                    ($fieldName !== null ? $fieldName . '.' : '') . $name
                );
            } catch (FieldError $e) {
                $errors[] = $e;
            } catch (TransformException $e) {
                $errors = [... $errors, ...$e->getErrors()];
            }
        }

        if (count($errors) > 0) {
            throw new TransformException(...$errors);
        }

        $obj = new $class;
        foreach ($finalData as $field => $value) {
            $obj->$field = $value;
        }

        return $obj;
    }

    /**
     * Check if scalar type is valid and return it's value
     *
     * @param string $type
     * @param mixed $data
     * @param string $fieldName
     * @return int|string|float|bool
     */
    private function checkScalar(string $type, $data, string $fieldName)
    {
        switch ($type) {
            case 'int':
                // Integer validation
                if (!is_int($data)) {
                    throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field must be integer']);
                }
                break;
            case 'string':
                // String validation
                if (!is_string($data)) {
                    throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field must be string']);
                }
                break;
            case 'float':
                // Float validation
                if (!is_float($data)) {
                    throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field must be float']);
                }
                break;
            case 'bool':
                // boolean validation
                if (!is_bool($data)) {
                    throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field must be boolean']);
                }
                break;
            default:
                throw new UnsupportedException('Unsupported built-in type: ' . $type);
                break;
        }
        return $data;
    }

    /**
     * Check if given type described by ReflectionType is valid and return modified value
     * for recursive calls support
     *
     * @param ReflectionType $type
     * @param mixed $data
     * @param string $fieldName
     * @return mixed
     */
    private function checkType(ReflectionType $type, $data, string $fieldName)
    {
        if (! $type->allowsNull() && $data === null) {
            // Field cannot have null
            throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field cannot have null value']);
        } elseif ($type->allowsNull() && $data === null) {
            // Field contains null, it's valid
            return $data;
        }

        $typeName = $type->getName();

        if ($type->isBuiltin()) {
            $data = $this->checkScalar($typeName, $data, $fieldName);
        } else {
            // Structured data validation
            if (class_exists($typeName)) {
                $class = new ReflectionClass($typeName);
                if ($class->isSubclassOf(Collection::class)) {
                    if (!is_array($data)) {
                        throw $this->to(FieldError::class, ['field' => $fieldName, 'reason' => 'Field must be array']);
                    }

                    // Collections have type hinted constructor parameter like "string ...$items"
                    $constructorParams = $class->getConstructor()->getParameters();
                    if (count($constructorParams) !== 1) {
                        throw new UnsupportedException('Collection with more than 1 argument is not supported');
                    }

                    // Check types recursive
                    $itemType = $constructorParams[0]->getType();
                    $objects = [];
                    foreach ($data as $key => $item) {
                        // TODO: is it right? what with fields required and nullable?
                        $objects[] = $this->checkType($itemType, $item, $fieldName . '.' . $key);
                    }
                    $data = new $typeName(...$objects);
                } else {
                    $data = $this->to($typeName, $data, $fieldName);
                }
            } else {
                throw new UnsupportedException('Unsupported nested type ' . $typeName);
            }
        }

        return $data;
    }
}
