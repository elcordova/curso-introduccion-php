<?php

namespace App\Traits;

trait HasDefaultJob {
  public function getImage() {
    return $this->image;
  }

  public function getMonths() {
    return $this->months;
  }
}
