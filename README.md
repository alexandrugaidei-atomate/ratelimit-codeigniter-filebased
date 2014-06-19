Codeigniter rate limiter helper
===============

Small helper for allow only a certain number of requests per a certain amount of minutes.

The code is released under an MIT license.

Usage
-----

```php
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('ratelimit');
        limitRequests($this->input->ip_address());
    }
```

By default is limit to 100 requests per 5 min, but you can indicates it in params:

```php
    limitRequests("key", 50, 120);
```

where 50 is number of frequests and 120 is time in seconds.
