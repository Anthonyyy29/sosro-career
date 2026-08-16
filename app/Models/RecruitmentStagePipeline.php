<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentStagePipeline extends Model
{
    protected $table = 'recruitment_stage_pipeline';

    protected $fillable = ['kategori', 'recruitment_stage_id', 'order'];

    public function stage()
    {
        return $this->belongsTo(RecruitmentStage::class, 'recruitment_stage_id');
    }
}
