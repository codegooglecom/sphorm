Lets assume we have already defined our [User](UserDomainBasic.md) domain.

Get all users:
```
$users = User()->all();
```

Get all users with parameters: _max_, _order_, _offset_

```
// max 10
$users = User()->all(array('max' => 10));

// max 10 with offset
$users = User()->all(array('max' => 10, 'offset' => 20));

// order by name
$users = User()->all(array('order' => 'name'));

// order by name desc
$users = User()->all(array('order' => array('desc' => 'name')));

```

Count all users:
```
$total = User()->count();
```

Count users with parameters:
```
$total = User()->count(array('name'=>'John', ...));
```

Getting user(s) by id(s):
```
// only one
$oneUser = User()->get(1024); 

// multiple at once
$threeUsers = User()->get(1024, 45, 56);

// identical, just another way to use it.
$threeUsers = User()->get(array(1024, 45, 56));

```

Find users:
```
// first match
$foundOneUser = User()->find(array('name'=>'John', ...));

// all matches
$foundUsers = User()->findAll(array('name'=>'John', ...));

// all matches with parameters (same as for all)
$users = User()->findAll(array('name' => 'Test'), array('max' => 4, 'offset' => 2, 'order' => array ('desc' => 'id')));
```

Generic queries, that Sphorm cannot handle yet
```
// will return an asocciative array, aka old style fetching...
$recs = SphormQuery()->execute('select UID, count(*) nrImgs, sum(ConvertTime) total, max(ConvertTime) maxTime from logs group by UID order by LogId desc');
```