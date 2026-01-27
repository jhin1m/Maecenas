<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');
        $email = $this->ask('Nhập email');
        $name = $this->ask('Nhập tên');
        $password = $this->secret('Nhập mật khẩu');

        $admin = new User();
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = bcrypt($password);
        $admin->role = '1';
        $admin->exp = 999999;
        $admin->save();

        $this->info('Tài khoản admin đã được tạo thành công!');
    }
}
