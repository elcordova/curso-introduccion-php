<?php
namespace App\Models;
use App\Traits\HasDefaultJob;
use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    use HasDefaultJob;
    protected $table = 'jobs';

    public function getDurationAsString() {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;
      
        return "Job duration: $years years $extraMonths months";
    }
}