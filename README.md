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
                'name' => 'Name',
                'email' => 'Email',
                'balance' => 'Balance',
                'created_at' => 'Created at'
            ])
            /*
            For filters allowed types: string, range, date
            */
            ->filters([
                'name' => 'string',
                'email' => 'string',
                'role_id' => (new SelectFilter('role_id'))
                    ->options($roles)
                    ->label('Role'),
                /*
                You can specify settings for some of the filters.
                Eg: range filter can take multiplier as the argument
                 */
                'balance' => 'range|multiplier:10000',                
                'created_at' => 'date'
            ])
            /*
            For totals allowed types: sum, count
            */
            ->totals([
                'balance' => 'sum',
                'id' => 'count'
            ])
            /*
            Sometimes you need to filter results without input
            If User with moderation role can't view admin users
            */
            ->filterCallback(function($model) {
                if (auth()->user() && auth()->user()->role->type == Role::TYPE_MODERATOR) {
                    $model = $model->whereHas('role', function($query) {
                        $query->where('type', '<>', Role::TYPE_ADMIN);
                    });
                }
                return $model;
            })
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
.table {
  tbody {
    .ctable-total-content {
      border: none;
      background-color: transparent !important;

      td {
        border: none;
        background-color: transparent;
        font-size: 80%;
        padding-top: 0;
      }
    }

    .ctable-total-heading {
      border: none;
      background-color: transparent !important;

      td {
        border: none;
        background-color: transparent;
        font-size: 80%;
        font-weight: bold;
      }
    }

    tr:nth-of-type(even) {
      td.ctable-ordered {
        background-color: rgba(0, 0, 0, 0.10);
      }
    }

    tr:nth-of-type(odd) {
      td.ctable-ordered {
        background-color: rgba(0, 0, 0, 0.17);
      }
    }
  }

  thead {
    a {
      text-decoration: none;
    }
  }

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
}
```

###Some recommendations for JS
 
 Use [Flatpickr](https://chmln.github.io/flatpickr/) library for datepicker 
```
require('flatpickr')('[data-toggle=datepicker]');
```