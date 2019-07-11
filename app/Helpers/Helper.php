<?php

namespace App\Helpers;

use Auth;
use Session;
use App\User;
use App\Models\Training\Course;
use App\Models\{People, Menu};
use App\Models\Ticket\Type;
use App\Models\{Department, Occupation, Module};
use App\Models\Ticket\Type\Category;
use App\Models\Unit;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;

/**
 *
 */
class Helper
{
    public static function slug($key)
    {
        $user = Auth::user();
        return (string)$user->id.'-'.$key;
    }

    public static function has($key)
    {
        $slug = self::slug($key);
        return Session::exists($slug);
    }

    public static function get($key)
    {
        $slug = self::slug($key);
        return Session::get($slug);
    }

    public static function set($key, $value)
    {
        $slug = self::slug($key);
        return Session::put($slug, $value);
    }

    public static function drop($key)
    {
        $slug = self::slug($key);
        return Session::forget($slug);
    }

    public static function courses()
    {
        $key = 'courses';

        if(self::has($key)) {
            return self::get($key);
        }

        $courses = Course::all();

        self::set($key, $courses);
        return self::get($key);
    }

    public static function roles()
    {
        $key = 'roles';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Role::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function units()
    {
        $key = 'units';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Unit::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function departments()
    {
        $key = 'departments';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Department::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function occupation($department)
    {
        $key = 'ocupation-'.$department;

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Occupation::where('department_id', $department)->get();

        self::set($key, $data);
        return self::get($key);
    }

    public static function modules()
    {
        $key = 'modules';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Module::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function permissions()
    {
        $key = 'permissions';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = Permission::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function users()
    {
        $key = 'users';

        if(self::has($key)) {
            return self::get($key);
        }

        $data = User::all();

        self::set($key, $data);
        return self::get($key);
    }

    public static function ticketCategories()
    {
        $key = 'ticket_type_categories';

        if(self::has($key)) {
            return self::get($key);
        }

        $types = Category::all();

        self::set($key, $types);
        return self::get($key);
    }

    public static function ticketTypes()
    {
        $key = 'ticket_types';

        if(self::has($key)) {
            return self::get($key);
        }

        $types = Type::all();

        self::set($key, $types);
        return self::get($key);
    }

    public static function departments()
    {
        $key = 'departments';

        if(self::has($key)) {
            return self::get($key);
        }

        $departments = Department::all();

        self::set($key, $departments);
        return self::get($key);
    }

    public static function menus()
    {
        $key = 'menus';

        if(self::has($key)) {
            return self::get($key);
        }

        $menus = Menu::all();

        self::set($key, $menus);
        return self::get($key);
    }

    public static function getRouteForModel($model, $subject)
    {
        $item = $model::find($subject);

        $route = null;
        $html = null;

        if($model == 'App\Models\Ticket') {

          if($item) {
            $route = route('tickets.show', $item->uuid);
            $html = "<a href=".$route.">".$item->type->name."</a>";
          }
        }

        if($model == 'App\Models\Ticket\Type') {

          if($item) {
            $route = route('ticket-types.index', $item->uuid);
            $html = "<a href=".$route."> ".$item->name."</a>";
          }
        }

        if($model == 'App\Models\Documents') {

          if($item) {
            $route = route('document_preview', $item->uuid);
            $html = "<a target='_blank' href=".$route."> " . $item->filename."</a>";
          }

        }

        if($model == 'App\Models\Training\Course') {

          if($item) {
            $route = route('courses.edit', $item->uuid);
            $html = "<a href=".$route.">".$item->title."</a>";
          }
        }

        if($model == 'App\User') {

          if($item) {
            $route = route('user', $item->uuid);
            $html = "<a href=".$route.">".$item->person->name."</a>";
          }
        }

        if($model == 'App\Models\MessageBoard\Type') {
          if($item) {
            $route = route('message-types.edit', $item->uuid);
            $html = '<a href='.$route.'>'.$item->name.'</a>';
          }
        }

        if($model == 'App\Models\Client') {

          if($item) {
            $route = route('clients.show', $item->uuid);
            $html = '<a href='.$route.'>'.$item->name.'</a>';
          }
        }

        if($model == 'App\Models\Department') {

          if($item) {
            $route = route('departments.show', $item->uuid);
            $html = '<a href='.$route.'>'.$item->name.'</a>';
          }
        }

        if($model == 'App\Models\Department\Occupation') {

          if($item) {
            $route = route('occupations.show', $item->uuid);
            $html = '<a href='.$route.'>'.$item->name.'</a>';
          }
        }

        if($model == 'App\Models\People') {
          $route = route('user', $item->uuid);
          $html = '<a href='.$route.'>'.$item->name.'</a>';
        }

        if($model == 'App\Models\Client\Address') {

          if($item) {
            $route = route('clients.show', $item->client->uuid);
            $html = '<a href='.$route.'>'.$item->description.': '.$item->street.', '.$item->number.', '.$item->district.', '.$item->city.', '.$item->zip.'</a>';
          }

        }

        if($model == 'App\Models\Client\Employee') {

          if($item) {
            $route = route('clients.show', $item->company->uuid);
            $html = '<a href='.$route.'>'.$item->name.'</a>';
          }

        }

        if($model == 'App\Models\MessageBoard') {
          $route = route('message-board.show', $item->uuid);
          $html = '<a href='.$route.'>'.$item->subject.'</a>';
        }

        return [
          'route' => $route,
          'html' => $html
        ];
    }

    public static function getTagHmtlForModel($model, $subject)
    {
        if(!$subject) {
          return null;
        }

        $itens = self::getRouteForModel($model, $subject);

        echo $itens['html'];
    }

    public static function idade(People $person)
    {
        $date = $person->birthday;
        $interval = $date->diff(now());

        return $interval->format( '%y Anos' );
    }

    public static function Initials($string = null) {
        return array_reduce(
            explode(' ', $string),
            function ($initials, $word) {
                return sprintf('%s%s', $initials, substr($word, 0, 1));
            },
            ''
        );
    }

}
