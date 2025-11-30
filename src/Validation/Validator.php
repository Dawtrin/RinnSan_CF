<?php

namespace Rinnsan\RinnSanWeb\Validation;

class Validator
{
    protected $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $fieldRules) {
            $rules_array = explode('|', $fieldRules);
            
            foreach ($rules_array as $rule) {
                $this->applyRule($field, $rule, $data);
            }
        }

        return empty($this->errors);
    }

    protected function applyRule($field, $rule, $data)
    {
        $value = $data[$field] ?? null;
        
        if (strpos($rule, ':') !== false) {
            [$rule_name, $rule_value] = explode(':', $rule);
        } else {
            $rule_name = $rule;
            $rule_value = null;
        }

        switch ($rule_name) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = "{$field} là bắt buộc";
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$field} phải là email hợp lệ";
                }
                break;
            case 'min':
                if (strlen($value) < $rule_value) {
                    $this->errors[$field][] = "{$field} phải có ít nhất {$rule_value} ký tự";
                }
                break;
            case 'max':
                if (strlen($value) > $rule_value) {
                    $this->errors[$field][] = "{$field} không được quá {$rule_value} ký tự";
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[$field][] = "{$field} phải là số";
                }
                break;
            case 'integer':
                if (!is_numeric($value) || (int)$value != $value) {
                    $this->errors[$field][] = "{$field} phải là số nguyên";
                }
                break;
            case 'decimal':
                if (!is_numeric($value)) {
                    $this->errors[$field][] = "{$field} phải là số thập phân";
                }
                break;
            case 'in':
                $allowed = explode(',', $rule_value);
                if (!in_array($value, $allowed)) {
                    $this->errors[$field][] = "{$field} phải là một trong: " . implode(', ', $allowed);
                }
                break;
            case 'unique':
                // Có thể implement sau nếu cần
                break;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
