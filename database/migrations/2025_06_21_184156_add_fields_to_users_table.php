<?php

declare(strict_types = 1);

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->string('cpf', 11)->unique();
            $table->string('job_position');
            $table->date('birth_date');
            $table->string('zip_code', 9);
            $table->string('street');
            $table->string('number', 20)->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state', 2);
            $table->enum('role', [UserRole::ADMIN->value, UserRole::EMPLOYEE->value])->default(UserRole::EMPLOYEE->value);
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');

            $table->softDeletes();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'cpf',
                'birth_date',
                'job_position',
                'role',
                'zip_code',
                'street',
                'number',
                'complement',
                'neighborhood',
                'city',
                'state',
                'manager_id',
                'deleted_at',
            ]);
        });
    }
};
