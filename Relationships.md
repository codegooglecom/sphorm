# Introduction #

Currently sphorm supports: One to One, One to Many relationships, unidirectional. Yes, in near future there will be more.


# One to One #
To be able to use One to One relationship, you need to let know Sphorm who is your domain's brother. In this case you need to add a static array, named 'hasOne' like this:
```
static $hasOne = array(
	  'profile' => array(                 // property name, $obj->profile
		'type' => 'Profile',          // it's type, $obj->profile = new Profile()
		'join' => 'ProfileUserID'     // Join column, aka FK
	  )
	);
```

You can have as many brothers as you want:
```
static $hasOne = array(
	  'profile' => array(                 
		'type' => 'Profile',          
		'join' => 'ProfileUserID'     
	   ),
          'otherProperty' => array(                 
		'type' => 'OtherClass',          
		'join' => 'OtherCOlumnId'     
	   ),
          ....
	);
```

# One to Many #
To define an One to Many relationship you have to make usage of 'hasMany' property:
```
static $hasMany = array(
		'addresses' => array(                  // $obj->addresses
			'type' => 'Address',           // array of Address instances  
			'join' => 'AddressUserID'      // same as for one to one
		),
	);
```

To add address(es) use `addTo*` method:
```
$newAddr = new Address();
$newAddr->street = 'New street';
$obj->addToAddresses($newAddr);

// or a shorter version
$obj->addToAddresses(new Address(array('street' => 'New address')));

// you can add multiple addresses at a time, a kind of addAll method...
$obj->addToAddresses(array(new Address(...), new Address(...), etc..))
```

Remove item(s) from collection by Id(s):
```
// remove the address with id=100
$obj->removeFromAddresses(100);

// or multiple at once
$obj->removeFromAddresses(100, 234);

// or multiple at once v2
$obj->removeFromAddresses(array(100, 234));

// make changes permanent
$obj->save();
```

# Requirements/Limitations #
  1. In all cases brothers or children have to be Sphorm domain classes (extend Sphorm).
  1. Brothers or childre are ALWAYS loaded lazy...aka they will be loaded on 1st access