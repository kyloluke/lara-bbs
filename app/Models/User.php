<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasRoles, MustVerifyEmailTrait, ActiveUserHelper;
    // 为了方便扩展该方法 取个别名
    use Notifiable {
    notify as protected BaseNotify;
    }

    // 这里的 $instance 就是
    public function notify($instance)
    {
        if (method_exists($instance, 'toDatabase')) {
            // 如果要通知的是自己，就略过
            if ($this->id === \Auth::id()) {
                return;
            }
            $this->increment('notification_count');
        }
        $this->BaseNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany('App\Models\Topic', 'user_id');
    }


    // 用于 policy 权限控制
    public function isAuthorOf($model)
    {
        return $this->id === $model->user_id;
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply', 'user_id');
    }
}
