<?php

namespace Craft\Reflect\Event;

use Craft\Reflect\Event;

class Channel implements \SplSubject
{

    use Event {
        fire as protected innerFire;
    };

    /** @var \SplObjectStorage */
    protected $observers;


    /**
     * Setup event channel
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }


    /**
     * Attach an SplObserver
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }


    /**
     * Detach an observer
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }


    /**
     * Notify an observer
     * @param null $event
     * @param array $params
     * @return int
     */
    public function notify($event = null, array $params = [])
    {
        $count = 0;
        foreach($this->observers as $observer) {
            $observer->update($this, $event, $params);
            $count++;
        }
        return $count;
    }


    /**
     * Fire event
     * @param string $event
     * @param array $params
     * @return int
     */
    public function fire($event, array $params = [])
    {
        // fire inner event
        $count = $this->innerFire($event, $params);

        // notify observers
        $count += $this->notify($event, $params);

        return $count;
    }

}