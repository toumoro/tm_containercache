<?php

declare(strict_types = 1);

namespace Toumoro\TmContainercache\Utility;

/***
 *
 * This file is part of the "Redis Container cache" Extension for TYPO3 CMS by Toumoro.com.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Toumoro.com (Simon Ouellet)
 *
 ***/

/**
 * Description of Connection
 *
 * @author simouel
 */
class RedisBackend extends \TYPO3\CMS\Core\Cache\Backend\RedisBackend {


   /**
     * Initializes the redis backend
     *
     * @throws \TYPO3\CMS\Core\Cache\Exception if access to redis with password is denied or if database selection fails
     */
    public function initializeObject()
    {

        $this->redis = new \Redis();
        try {
            if ($this->persistentConnection) {
                $this->connected = $this->redis->pconnect($this->hostname, $this->port, $this->connectionTimeout);
            } else {
                $this->connected = $this->redis->connect($this->hostname, $this->port, $this->connectionTimeout);
            }
        } catch (\Exception $e) {
            \TYPO3\CMS\Core\Utility\GeneralUtility::sysLog('Could not connect to redis server.', 'core', \TYPO3\CMS\Core\Utility\GeneralUtility::SYSLOG_SEVERITY_ERROR);
        }
        if ($this->connected) {
            
            if ($this->password !== '') {
                $success = $this->redis->auth($this->password);
                if (!$success) {
                    throw new \TYPO3\CMS\Core\Cache\Exception('The given password was not accepted by the redis server.', 1279765134);
                }
            }
            if ($this->database >= 0) {
                $success = $this->redis->select($this->database);
                if (!$success) {
                    throw new \TYPO3\CMS\Core\Cache\Exception('The given database "' . $this->database . '" could not be selected.', 1279765144);
                }
            }

            
           /* if (!$this->has($_ENV["HOSTNAME"])) {
                exec("rm -rf typo3temp/var/Cache/Code/cache_core/*",$output);
                print_r($clear);
                $this->set($_ENV["HOSTNAME"],"1");
            }*/
            
            
        }
    }
    
        /**
     * Save data in the cache
     *
     * Scales O(1) with number of cache entries
     * Scales O(n) with number of tags
     *
     * @param string $entryIdentifier Identifier for this specific cache entry
     * @param string $data Data to be stored
     * @param array $tags Tags to associate with this cache entry
     * @param int $lifetime Lifetime of this cache entry in seconds. If NULL is specified, default lifetime is used. "0" means unlimited lifetime.
     * @throws \InvalidArgumentException if identifier is not valid
     * @throws \TYPO3\CMS\Core\Cache\Exception\InvalidDataException if data is not a string
     * @api
     */
    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null)
    {
        $entryIdentifier = $_ENV["PGU_BUILD"].'-'.$entryIdentifier;
        parent::set($entryIdentifier, $data, $tags, $lifetime);
    }
    
        /**
     * Loads data from the cache.
     *
     * Scales O(1) with number of cache entries
     *
     * @param string $entryIdentifier An identifier which describes the cache entry to load
     * @return mixed The cache entry's content as a string or FALSE if the cache entry could not be loaded
     * @throws \InvalidArgumentException if identifier is not a string
     * @api
     */
    public function get($entryIdentifier)
    {
        $entryIdentifier = $_ENV["PGU_BUILD"].'-'.$entryIdentifier;
        return parent::get($entryIdentifier);
    }
        /**
     * Checks if a cache entry with the specified identifier exists.
     *
     * Scales O(1) with number of cache entries
     *
     * @param string $entryIdentifier Identifier specifying the cache entry
     * @return bool TRUE if such an entry exists, FALSE if not
     * @throws \InvalidArgumentException if identifier is not a string
     * @api
     */
    public function has($entryIdentifier)
    {
       $entryIdentifier = $_ENV["PGU_BUILD"].'-'.$entryIdentifier;
       return parent::has($entryIdentifier);
    }
}
