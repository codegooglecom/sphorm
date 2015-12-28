Yet another PHP ORM project which wants to bring joy and fun to PHP Web developers.

# Quick start #

Basic domain class, saved as `User.php`
```
class User extends Sphorm {
	// mapping
	static $mapping = array(
		'table' => 'users',
		'id' => array(
			'column'=> 'UserID',
			'generator' => 'auto'
		),
		'columns' => array(
			'name' => 'UserName',
			'age' => 'UserAge'
		)
	);
	
	public function __construct(array $init = array()) {
		parent::__construct($init);
	}
}
```

Your `index.php` or other script that needs to use `User`
```
require "[path-to-sphorm-dir]/inc.php";

// rest of your code
```


### Now you can: ###

Count the number of instances in the database and returns the result
```
$total = User()->count();
```

Retrieve all of the domain class instances
```
$allUsers = User()->all();
```


Retrieve an instance of the domain class for the specified id, otherwise returns null
```
$oneUser = User()->get(1024); 
```


Retrieve an array of instances of the domain class for the specified ids, otherwise returns empty array
```
$multipleUsers = User()->get(1024, 45, 56);
$multipleUsers = User()->get(array(1024, 45, 56));
```


Find and return the first result for the given params (assoc array) or null if no instance was found. Keys are instance properties, or columns if they don't have properties mapped.
```
$foundOneUser = User()->find(array('name'=>'John'));
```


Find all of the domain class instances for the specified params
```
$foundUsers = User()->findAll(array('name'=>'John'));
```



Create new instance
```
$newUser = new User();
$newUser->name = 'Maria';
$newUser->age = 24;
```


Creates a new record

```
$success = $newUser->save();
```


2nd time will try to update it

```
$success = $newUser->save();
```


Delete
```
$badUser = User()->get(567);
if ($badUser != null) {
   $badUser->delete();
}
```


# Features #
  1. Basic object to table mapping.
  1. One to One relation with lazy init.
  1. One to Many relation with lazy.
  1. Table generation(for best results it's required to define all column name and types)
  1. etc...

### Check the 'samples' directory for more examples(it comes with sphorm package) ###