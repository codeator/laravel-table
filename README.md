# Laravel table
Laravel module for table rendering

##Installation
First, require the package using Composer:

`composer require codeator/laravel-table`

Add to your config/app.php providers section

`Codeator\Table\Providers\TableServiceProvider::class`

and to facades section

`'Table' => Codeator\Table\Facades\Table::class`

##Examples

##Translations

Soon

###Table class

```
<?php

namespace App\Tables\Admin;

use App\Models\User;
use App\Models\Role;
use Table;
use Codeator\Table\Filter\SelectFilter;

class UsersTable extends Table
{

    public static function create() {
        $roles = Role::all()->pluck('name', 'id');
        $table = self::from(new User())
            ->columns([
                'id' => 'Id',
                'name' => 'Имя',
                'email' => 'Email',
                'balance' => 'Баланс',
                'created_at' => 'Регистрация'
            ])
            /*
            For filters allowed types: string, range, date
            */
            ->filters([
                'name' => 'string',
                'email' => 'string',
                'role_id' => (new SelectFilter('role_id'))
                    ->options($roles)
                    ->label('Роль'),
                /*
                You can specify settings for some of the filters.
                Eg: range filter can take multiplier as the argument
                 */
                'balance' => 'range|multiplier:10000',
                'count_maps' => 'range',
                'count_folders' => 'range',
                'count_objects' => 'range',
                'created_at' => 'date'
            ])
            /*
            Group operations for query
            Label for delete batch action is: trans('table::batch.labels.delete');
            */
            ->batchActions([
                'delete' => function ($queryBuilder) {
                    $queryBuilder->delete();
                }
            ])
            ->sortables([
                'id'
            ])
            ->exporters(['csv'])
            /*
                Route name -> Link name in last column
            */
            ->actions([
                'admin.users.view' => 'View'
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

###Sample Sass based on bootstrap v4

```
.table-filter {
  margin-bottom: 20px;

  .form-group {
    width: 25%;
    padding-left: 15px;
    padding-right: 15px;
    float: left;

    .col-xs-6 {
      &:first-of-type {
        padding-right: 5px;
      }

      &:last-of-type {
        padding-left: 5px;
      }
    }
  }
}

//For use with materialdesignicons.com
.table-arrow-up:before {
  font: normal normal normal 16px/1 "Material Design Icons";
  content: mdi('arrow-up');
  text-decoration: none;
}

.table-arrow-down:before {
  font: normal normal normal 16px/1 "Material Design Icons";
  content: mdi('arrow-down');
  text-decoration: none;
}
```

###Some recommendations for JS
 
 Use [Flatpickr](https://chmln.github.io/flatpickr/) library for datepicker 
```
require('flatpickr')('[data-toggle=datepicker]');
```