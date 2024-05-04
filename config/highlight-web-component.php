<?php

return [
    /**
     * Cache the highlighted code on the server and use it
     * before highlighting the code again.
     */
    'cache-on-server' => true,

    /**
     * How long to cache on the server for. Supports anything
     * the cache driver supports as TTL.
     */
    'cache-on-server-ttl' => 60 * 60 * 24,

    /**
     * Cache the highlighted code on the client and use it
     * before querying server for freshly highlighted code.
     */
    'cache-on-client' => true,
];
