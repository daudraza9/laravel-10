<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    // $users = DB::table('users')->first();
    // $users = User::find(6);
    // $user = User::Create([
    //     'name'=> 'tuesday',
    //     'email'=>'tuesday@gmail.com',
    //     'password'=>'12345678'
    // ]);
    // $user = User::where('id',4)->update([
    //     'email'=>'tuesdayusers@gmail.com',
    // ]);
    // $users = DB::select('select * from users');
    // User::find(4)->delete();
    // $user = DB::insert('insert into users (name,email,password) values(?,?,?)',[
    //     'David',
    //     'david@gmail.com',
    //     '12345678'
    // ]);
    // $user = DB::update('update users set email="abc@gmail.com" where id = 2');
    // $user = DB::delete('delete from users where id = 2');
    // $user = DB::table('users')->insert([
    //     'name'=> 'Conovo',
    //     'email'=>'conovo@gmail.com',
    //     'password'=>'12345678'
    // ]);
//     $user = User::create([
// 'name'=> 'tuesday',
//         'email'=>'tuesdaymodayq@gmail.com',
//         'password'=>'12345678'
//     ]);
    // dd($users->name);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

require __DIR__.'/auth.php';


Route::get('/openai',function(){
    // use OpenAI\Laravel\Facades\OpenAI;

    $result = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello!'],
        ],
    ]);
    
    echo $result->choices[0]->message->content; // Hello! How can I assist you today?
});