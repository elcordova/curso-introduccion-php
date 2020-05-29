<?php
namespace App\Services;

class JobService {
  public function deleteJob($id) {
        $job = Job::findOrFail($id);
        $job->delete();
  }
}