### - [Readme](README.md)
### - Endpoints
### - [Rest test config](requests-expects.http)
### - [ipkiss.pragmazero.com](http://ipkiss.pragmazero.com)


----
### Reset state before starting tests

```http
POST /reset
```


```sh
# Expected
200 OK
```


----
### Get balance for non-existing account

```http
GET /balance?account_id=1234
```


```sh
# Expected
404 0
```


----
### Create account with initial balance

```http
POST /event {"type":"deposit", "destination":"100", "amount":10}
```


```sh
# Expected
201 {"destination": {"id":"100", "balance":10}}
```


----
### Deposit into existing account

```http
POST /event {"type":"deposit", "destination":"100", "amount":10}
```


```sh
# Expected
201 {"destination": {"id":"100", "balance":20}}
```


----
### Get balance for existing account

```http
GET /balance?account_id=100
```

```sh
# Expected
200 20
```

----
### Withdraw from non-existing account

```http
POST /event {"type":"withdraw", "origin":"200", "amount":10}
```

```sh
# Expected
404 0
```

----
### Withdraw from existing account

```http
POST /event {"type":"withdraw", "origin":"100", "amount":5}
```

```sh
# Expected
201 {"origin": {"id":"100", "balance":15}}
```

----
### Transfer from existing account

```http
POST /event {"type":"transfer", "origin":"100", "amount":15, "destination":"300"}
```

```sh
# Expected
201 {"origin": {"id":"100", "balance":0}, "destination": {"id":"300", "balance":15}}
```

----
### Transfer from non-existing account

```http
POST /event {"type":"transfer", "origin":"200", "amount":15, "destination":"300"}
```

```sh
# Expected
404 0
```

