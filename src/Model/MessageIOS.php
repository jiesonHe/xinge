<?php

namespace Gutplushe\ApnsPHP\Model;

use Gutplushe\ApnsPHP\Model\TimeInterval;

class MessageIOS
{
    public function __construct()
    {
        $this->m_acceptTimes = array();
    }
    public function setExpireTime($expireTime)
    {
        $this->m_expireTime = $expireTime;
    }
    public function getExpireTime()
    {
        return $this->m_expireTime;
    }
    public function setSendTime($sendTime)
    {
        $this->m_sendTime = $sendTime;
    }
    public function getSendTime()
    {
        return $this->m_sendTime;
    }
    public function addAcceptTime($acceptTime)
    {
        $this->m_acceptTimes[] = $acceptTime;
    }
    public function acceptTimeToJson()
    {
        $ret = array();
        foreach ($this->m_acceptTimes as $acceptTime)
        {
            $ret[] = $acceptTime->toArray();
        }
        return $ret;
    }
    public function setCustom($custom)
    {
        $this->m_custom = $custom;
    }
    public function setRaw($raw)
    {
        $this->m_raw = $raw;
    }
    public function setAlert($alert)
    {
        $this->m_alert = $alert;
    }
    public function setBadge($badge)
    {
        $this->m_badge = $badge;
    }
    public function setSound($sound)
    {
        $this->m_sound = $sound;
    }
    public function getType()
    {
        return 0;
    }
    public function getCategory()
    {
        return $this->m_category;
    }
    public function setCategory($category)
    {
        $this->m_category = $category;
    }
    public function getLoopInterval()
    {
        return $this->m_loopInterval;
    }
    public function setLoopInterval($loopInterval)
    {
        $this->m_loopInterval = $loopInterval;
    }
    public function getLoopTimes()
    {
        return $this->m_loopTimes;
    }
    public function setLoopTimes($loopTimes)
    {
        $this->m_loopTimes = $loopTimes;
    }

    public function toJson()
    {
        if(!empty($this->m_raw)) return $this->m_raw;
        $ret = $this->m_custom;
        $aps = array();
        $ret['accept_time'] = $this->acceptTimeToJson();
        $aps['alert'] = $this->m_alert;
        if(isset($this->m_badge)) $aps['badge'] = $this->m_badge;
        if(isset($this->m_sound))$aps['sound'] = $this->m_sound;
        if(isset($this->m_category))$aps['category'] = $this->m_category;
        $ret['aps'] = $aps;
        return json_encode($ret);
    }

    public function isValid()
    {
        if (is_string($this->m_raw) && !empty($this->raw)) return true;
        if (isset($this->m_expireTime))
        {
            if(!is_int($this->m_expireTime) || $this->m_expireTime>3*24*60*60)
                return false;
        }
        else
        {
            $this->m_expireTime = 0;
        }

        if(isset($this->m_sendTime))
        {
            if(strtotime($this->m_sendTime)===false) return false;
        }
        else
        {
            $this->m_sendTime = "2014-03-13 12:00:00";
        }

        foreach ($this->m_acceptTimes as $value)
        {
            if(!($value instanceof TimeInterval) || !$value->isValid())
                return false;
        }

        if(isset($this->m_custom))
        {
            if(!is_array($this->m_custom))
                return false;
        }
        else
        {
            $this->m_custom = array();
        }
        if(!isset($this->m_alert)) return false;
        if(!is_string($this->m_alert) && !is_array($this->m_alert))
            return false;
        if(isset($this->m_badge))
        {
            if (!is_int($this->m_badge))
                return false;
        }
        if(isset($this->m_sound))
        {
            if (!is_string($this->m_sound))
                return false;
        }
        if(isset($this->m_loopInterval)) {
            if(!(is_int($this->m_loopInterval) && $this->m_loopInterval > 0)) {
                return false;
            }
        }
        if(isset($this->m_loopTimes)) {
            if(!(is_int($this->m_loopTimes) && $this->m_loopTimes > 0)) {
                return false;
            }
        }
        if(isset($this->m_loopInterval) && isset($this->m_loopTimes)) {
            if(($this->m_loopTimes - 1) * $this->m_loopInterval + 1 > self::MAX_LOOP_TASK_DAYS) {
                return false;
            }
        }

        return true;
    }


    private $m_expireTime;
    private $m_sendTime;
    private $m_acceptTimes;
    private $m_custom;
    private $m_raw;
    private $m_alert;
    private $m_badge;
    private $m_sound;
    private $m_category;
    private $m_loopInterval;
    private $m_loopTimes;

    const MAX_LOOP_TASK_DAYS = 15;
}