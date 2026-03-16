<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;
use App\Models\StageTransition;

/**
 * Class StageConditionService
 *
 * Сервис проверки условий перехода между стадиями.
 *
 * Проверяет все условия, привязанные к конкретному переходу.
 * Если хотя бы одно условие не выполнено — переход запрещается.
 */
class StageConditionService
{
    /**
     * Проверить все условия перехода
     *
     * @param ServiceRequest $request
     * @param StageTransition $transition
     * @return bool
     */
    public function check(ServiceRequest $request, StageTransition $transition): bool
    {
        $transition->loadMissing('conditions');

        foreach ($transition->conditions as $condition) {
            $field = $condition->field;
            $operator = $condition->operator;
            $expectedValue = $condition->value;

            /**
             * Сначала пробуем взять значение как обычное поле модели.
             * Например: priority, status, assigned_to
             */
            $actualValue = $request->{$field} ?? null;

            /**
             * Если такого поля нет в самой заявке,
             * пробуем взять его из JSON-поля data.
             */
            if ($actualValue === null && is_array($request->data ?? null)) {
                $actualValue = $request->data[$field] ?? null;
            }

            if (!$this->compare($actualValue, $operator, $expectedValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Сравнение значений по оператору
     *
     * @param mixed $actualValue
     * @param string $operator
     * @param mixed $expectedValue
     * @return bool
     */
    protected function compare(mixed $actualValue, string $operator, mixed $expectedValue): bool
    {
        /**
         * Нормализуем булевы и числовые значения,
         * потому что из БД value приходит строкой.
         */
        $actual = $this->normalizeValue($actualValue);
        $expected = $this->normalizeValue($expectedValue);

        return match ($operator) {
            '=', '==' => $actual == $expected,
            '!=', '<>' => $actual != $expected,
            '>' => $actual > $expected,
            '<' => $actual < $expected,
            '>=' => $actual >= $expected,
            '<=' => $actual <= $expected,
            'in' => is_array($expected) ? in_array($actual, $expected, true) : false,
            'not_in' => is_array($expected) ? !in_array($actual, $expected, true) : false,
            default => false,
        };
    }

    /**
     * Нормализация значения
     *
     * @param mixed $value
     * @return mixed
     */
    protected function normalizeValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === 'true') {
                return true;
            }

            if ($trimmed === 'false') {
                return false;
            }

            if (is_numeric($trimmed)) {
                return str_contains($trimmed, '.') ? (float) $trimmed : (int) $trimmed;
            }

            /**
             * Поддержка формата: "a,b,c"
             */
            if (str_contains($trimmed, ',')) {
                return array_map(
                    static fn ($item) => trim($item),
                    explode(',', $trimmed)
                );
            }

            return $trimmed;
        }

        return $value;
    }
}
