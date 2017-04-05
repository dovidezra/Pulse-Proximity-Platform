<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class User extends Authenticatable implements StaplerableInterface
{
  use Notifiable;
  use EloquentTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password', 'api_token',
  ];

  protected $casts = [
    'settings' => 'json'
  ];

  public function __construct(array $attributes = array()) {
      $this->hasAttachedFile('avatar', [
          'styles' => [
              'default' => '128x128#'
          ]
      ]);

      parent::__construct($attributes);
  }

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  public function getDates() {
    return array('created_at', 'updated_at', 'last_login', 'expires');
  }

  public function getPlanName() {
    return ($this->plan_id != NULL) ? $this->plan->name : trans('global.free');
  }

  public function getPlanId() {
    return ($this->plan_id != NULL) ? $this->plan->id : 0;
  }

  public function getAvatar() {
    if ($this->avatar_file_name != NULL) {
      return $this->avatar->url('default');
    } else {
      return \Avatar::create(strtoupper($this->name))->toBase64();
    }
  }

  public function hasRole($roles) {
    return in_array($this->role, $roles);
  }

  public function parentUser() {
    return $this->belongsTo('\App\User', 'parent_id');
  }

  public function childUsers() {
    return $this->hasMany('\App\User', 'parent_id');
  }

  public function plan() {
    return $this->belongsTo('\App\Plan', 'plan_id');
  }

  public function reseller() {
    return $this->hasOne('\App\Reseller', 'reseller_id');
  }

  public function campaigns() {
    return $this->hasMany('\Platform\Models\Campaigns\Campaign', 'user_id');
  }

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token) {
      $this->notify(new ResetPasswordNotification($token));
  }
}