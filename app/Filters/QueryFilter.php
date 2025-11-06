<?php namespace App\Filters;

abstract class QueryFilter {
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @param string $condition
     * @param $value
     * @return string
     */
    function buildWhereAbout(string $condition, $value, $whereAbout = null): string
    {
        $whereAbout = ($whereAbout) ? $whereAbout . "(" : "(";
        $like_conditions = $this->getLikeConditions($value);
        foreach ($like_conditions as $key => $like_condition) {
            $whereAbout .= $key == 0
                ? "pris_translations.about $condition $like_condition"
                : " OR pris_translations.about $condition $like_condition";
        }
        $whereAbout .= ")";
        return $whereAbout;
    }

    /**
     * @param string $condition
     * @param $value
     * @return string
     */
    function buildWhereLegalReason(string $condition, $value, $whereLegalReason = null): string
    {
        $whereLegalReason = ($whereLegalReason) ? $whereLegalReason . "(" : "(";
        $like_conditions = $this->getLikeConditions($value);
        foreach ($like_conditions as $key => $like_condition) {
            $whereLegalReason .= $key == 0
                ? "pris_translations.legal_reason $condition $like_condition"
                : " OR pris_translations.legal_reason $condition $like_condition";
        }
        $whereLegalReason .= ")";
        return $whereLegalReason;
    }

    /**
     * @param string $condition
     * @param $value
     * @return string
     */
    function buildWhereChanges(string $condition, $value, $whereChanges = null): string
    {
        $whereChanges = ($whereChanges) ? $whereChanges . "(" : "(";
        $like_conditions = $this->getLikeConditions($value);
        foreach ($like_conditions as $key => $like_condition) {
            $whereChanges .= $key == 0
                ? "full_text $condition $like_condition"
                : " OR full_text $condition $like_condition";
        }
        $whereChanges .= ")";
        return $whereChanges;
    }

    /**
     * @param $value
     * @return array
     */
    private function getLikeConditions($value): array
    {
        return ["'% $value %'", "'% $value'", "'$value %'", "'$value,%'", "'$value/%'", "'% $value,%'", "'% $value/%'"];
    }
}
