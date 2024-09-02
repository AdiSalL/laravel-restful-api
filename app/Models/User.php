<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "username",
        "password",
        "name",
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, "user_id", "id");
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName() {
        return "username";
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier(){
        return $this->username;
    }

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName() {
        return $this->password;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return "token";
    }
}
