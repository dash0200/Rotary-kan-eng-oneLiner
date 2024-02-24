<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionModel extends Model
{
    use HasFactory; use SoftDeletes;
    
    protected $dates = ['deleted_at','date_of_adm', 'dob'];
    protected $table = 'admission';
    protected $fillable = [
        'id',
        "date_of_adm",
        "year",
        "caste",
        "sub_caste",
        "category",
        "class",
        "sts",
        "name",
        "fname",
        "mname",
        "lname",
        "address",
        "city",
        "phone",
        "mobile",
        "dob",
        "birth_place",
        "sub_district",
        "religion",
        "nationality",
        "gender",
        "handicap",
        "prev_school"
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function setFnameAttribute($value)
    {
        $this->attributes['fname'] = strtoupper($value);
    }

    public function setMnameAttribute($value)
    {
        $this->attributes['mname'] = strtoupper($value);
    }

    public function setLnameAttribute($value)
    {
        $this->attributes['lname'] = strtoupper($value);
    }

    public function setReligionAttribute($value)
    {
        $this->attributes['religion'] = strtoupper($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtoupper($value);
    }

    public function setBirthPlaceAttribute($value)
    {
        $this->attributes['birth_place'] = strtoupper($value);
    }

    public function acaYear() {
        return $this->hasOne(AcademicYearModel::class, 'id', 'year');
    }
    
    public function district() {
        return $this->hasOne(SubdistrictModel::class, 'id', 'sub_district');
    }

    public function classes() {
        return $this->hasOne(ClassesModel::class, 'id', 'class');
    }

    public function stdCast() {
        return $this->hasOne(CasteModel::class, 'id', 'caste');
    }

    public function subCaste() {
        return $this->hasOne(SubcastModel::class, 'id', 'sub_caste');
    }

    public function subDistrict() {
        return $this->hasOne(SubdistrictModel::class, 'id', 'sub_district');
    }

}
