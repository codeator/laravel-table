# Laravel table
Laravel module for table rendering

##Installation

Add to your config/app.php providers section

`Codeator\Table\Providers\TableServiceProvider::class`

and to facades section

`'Table' => Codeator\Table\Facades\Table::class`

##Examples

###Table class

```
<?php

namespace App\Tables\Admin;

use App\Models\User;
use Table;


class UsersTable extends Table
{

    public static function create() {
        $table = self::from(new User())
            ->columns([
                'id' => 'Id',
                'name' => 'Имя',
                'email' => 'Email',                
                'created_at' => 'Регистрация'
            ])
            ->sortables([
                'id'
            ])
            ->orderBy('id', 'asc')
            ->paginate(20);

        return $table;
    }

}
```

###Controller

```
<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Tables\Admin\UsersTable;

class UsersController extends Controller
{

    public function index() {
        $table = UsersTable::create();

        return view('admin.users.index', [
            'table' => $table
        ]);
    }

}
```

###View

```
@extends('admin.layout')

@section('content')
    {!! $table->render() !!}
@stop
```