<?php

/**
 * SyDES - Lightweight CMF for a simple sites with SQLite database
 *
 * @package   SyDES
 * @copyright 2011-2016, ArtyGrand <artygrand.ru>
 * @license   GNU GPL v3 or later; see LICENSE
 */

namespace App;

class Event {

    protected $events = [];

    /**
     * @param string   $event
     * @param string   $routes
     * @param callable $callback
     * @param int      $priority
     */
    public function on($event, $routes, $callback, $priority = 0) {
        if (!isset($this->events[$event])) $this->events[$event] = [];
        $this->events[$event][] = ['routes' => $routes, 'fn' => $callback, 'prio' => $priority];
    }

    /**
     * @param string $event
     */
    public function off($event) {
        if (!isset($this->events[$event])) {
            $this->events[$event] = [];
        }
    }

    /**
     * @param string $event
     * @param array  $params
     */
    public function trigger($event, $params = []) {
        if (empty($this->events[$event])) {
            return;
        }

        $queue = new \SplPriorityQueue();
        foreach ($this->events[$event] as $index => $action) {
            $queue->insert($index, $action['prio']);
        }

        $queue->top();
        while ($queue->valid()) {
            $index = $queue->current();
            if (isset($this->route)) {
                $routes = explode(',', $this->events[$event][$index]['routes']);
                $current_route = false;
                foreach ($routes as $route) {
                    if (fnmatch(trim($route), $this->route)) {
                        $current_route = true;
                        break;
                    }
                }
            } else {
                $current_route = true;
            }
            if ($current_route && is_callable($this->events[$event][$index]['fn'])) {
                if (call_user_func_array($this->events[$event][$index]['fn'], $params) === false) {
                    break;
                }
            }
            $queue->next();
        }
    }

}
