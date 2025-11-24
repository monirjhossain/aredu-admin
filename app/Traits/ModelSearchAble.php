<?php

namespace App\Traits;

trait ModelSearchAble
{
    public function scopeSearchable($query, array $searchSettings)
    {
        foreach ($searchSettings as $value) {
            if ($value['relation'] === '') {
                if ($value['type'] === 'where') {
                    if ($value['column_name'] == 'status' || $value['column_name'] == 'payment_status') {
                        $query->when($value['value'] > -1, function ($query) use ($value) {
                            $query->where($value['column_name'], $value['value']);
                            // dd($value['value']);
                        });
                    } else {
                        $query->when($value['value'], function ($query) use ($value) {
                            $query->where($value['column_name'], $value['value']);
                        });
                    }
                } elseif ($value['type'] === 'like') {
                    $query->when($value['value'], function ($query) use ($value) {
                        $query->where($value['column_name'], 'LIKE', '%' . $value['value'] . '%');
                    });
                } elseif ($value['type'] === 'like_start') {
                    $query->when($value['value'], function ($query) use ($value) {
                        $query->where($value['column_name'], 'LIKE', '' . $value['value'] . '%');
                    });
                } elseif ($value['type'] === 'not_like') {
                    $query->when($value['value'], function ($query) use ($value) {
                        $query->where($value['column_name'], 'NOT LIKE', '%' . $value['value'] . '%');
                    });
                } elseif ($value['type'] === 'not_like_start') {
                    $query->when($value['value'], function ($query) use ($value) {
                        $query->where($value['column_name'], 'NOT LIKE', '' . $value['value'] . '%');
                    });
                } elseif ($value['type'] == 'where_raw') {
                    $query->whereRaw($value['where_raw_query'] . " like '%" . $value['value'] . "%' ");
                }
            } else {
                if ($value['type'] === 'like') {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            if($value['same_relation']) {
                                $relation = $value['relation'];
                                $secondRelation = explode(".", $relation[1]); //example documentable.proformaInvoices

                                $query->whereHas($relation[0], function ($q) use ($value, $secondRelation) {
                                    $q->where($value['column_name'], 'like', '%' . $value['value'] . '%')
                                    ->orWhereHas($secondRelation[1], function ($q) use ($value) {
                                        $q->where($value['column_name'], 'like', '%' . $value['value'] . '%');
                                    });
                                });
                            } else {
                                foreach ($value['relation'] as $relation) {                                
                                    $query->orWhereHas($relation, function ($q) use ($value) {
                                        $q->where($value['column_name'], 'like', '%' . $value['value'] . '%');
                                    });
                                }
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->where($value['column_name'], 'like', '%' . $value['value'] . '%');
                            });
                        }
                    });
                } else if ($value['type'] === 'like_start') {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            if($value['same_relation']) {
                                $relation = $value['relation'];
                                $secondRelation = explode(".", $relation[1]); //example documentable.proformaInvoices
                                $query->whereHas($relation[0], function ($q) use ($value, $secondRelation) {
                                    $q->where($value['column_name'], 'like', '' . $value['value'] . '%')
                                    ->orWhereHas($secondRelation[1], function ($q) use ($value) {
                                        $q->where($value['column_name'], 'like', '' . $value['value'] . '%');
                                    });
                                });
                            } else {
                                foreach ($value['relation'] as $relation) {                                
                                    $query->orWhereHas($relation, function ($q) use ($value) {
                                        $q->where($value['column_name'], 'like', '' . $value['value'] . '%');
                                    });
                                }
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->where($value['column_name'], 'like', '' . $value['value'] . '%');
                            });
                        }
                    });
                } else if ($value['type'] === 'not_like') {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            if($value['same_relation']) {
                                $relation = $value['relation'];
                                $secondRelation = explode(".", $relation[1]); //example documentable.proformaInvoices

                                $query->whereHas($relation[0], function ($q) use ($value, $secondRelation) {
                                    $q->where($value['column_name'], 'NOT LIKE', '%' . $value['value'] . '%')
                                    ->orWhereHas($secondRelation[1], function ($q) use ($value) {
                                        $q->where($value['column_name'], 'NOT LIKE', '%' . $value['value'] . '%');
                                    });
                                });
                            } else {
                                foreach ($value['relation'] as $relation) {                                
                                    $query->orWhereHas($relation, function ($q) use ($value) {
                                        $q->where($value['column_name'], 'NOT LIKE', '%' . $value['value'] . '%');
                                    });
                                }
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->where($value['column_name'], 'NOT LIKE', '%' . $value['value'] . '%');
                            });
                        }
                    });
                } else if ($value['type'] === 'not_like_start') {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            if($value['same_relation']) {
                                $relation = $value['relation'];
                                $secondRelation = explode(".", $relation[1]); //example documentable.proformaInvoices
                                $query->whereHas($relation[0], function ($q) use ($value, $secondRelation) {
                                    $q->where($value['column_name'], 'NOT LIKE', '' . $value['value'] . '%')
                                    ->orWhereHas($secondRelation[1], function ($q) use ($value) {
                                        $q->where($value['column_name'], 'NOT LIKE', '' . $value['value'] . '%');
                                    });
                                });
                            } else {
                                foreach ($value['relation'] as $relation) {                                
                                    $query->orWhereHas($relation, function ($q) use ($value) {
                                        $q->where($value['column_name'], 'NOT LIKE', '' . $value['value'] . '%');
                                    });
                                }
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->where($value['column_name'], 'NOT LIKE', '' . $value['value'] . '%');
                            });
                        }
                    });
                } else if ($value['type'] == 'where_raw') {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            foreach ($value['relation'] as $relation) {
                                $query->orWhereHas($relation, function ($q) use ($value) {
                                    $q->whereRaw($value['where_raw_query'] . " like '%" . $value['value'] . "%' ");
                                });
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->whereRaw($value['where_raw_query'] . " like '%" . $value['value'] . "%' ");
                            });
                        }
                    });
                } else {
                    $query->when($value['value'], function ($query) use ($value) {
                        if(is_array($value['relation'])) {
                            foreach ($value['relation'] as $relation) {
                                $query->orWhereHas($relation, function ($q) use ($value) {
                                    $q->where($value['column_name'], $value['value']);
                                });
                            }
                        } else {
                            $query->whereHas($value['relation'], function ($q) use ($value) {
                                $q->where($value['column_name'], $value['value']);
                            });
                        }
                    });
                }
            }
        }
    }
}