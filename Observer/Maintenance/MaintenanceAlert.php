<?php

namespace Creatuity\Base\Observer\Maintenance;

use Creatuity\Base\Observer\AbstractObserver;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\Observer;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class MaintenanceAlert extends AbstractObserver
{
    private const DISABLE_MAINTENANCE_ALERT_MARKER_NAME = 'cb2_disable_maintenance_info';
    private const DEFAULT_MAINTENANCE_ALERT_MESSAGE = '----- WARNING!!! You are in maintenance mode ----';

    private MaintenanceMode $maintenanceMode;
    private string $maintenanceAlertMessage;

    public function __construct(MaintenanceMode $maintenanceMode, $maintenanceAlertMessage = '')
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->maintenanceAlertMessage = $maintenanceAlertMessage ?: static::DEFAULT_MAINTENANCE_ALERT_MESSAGE;
    }

    public function execute(Observer $observer): void
    {
        if (is_a($observer->getData('response'), Http::class, true)
            && $this->isDisplayMaintenanceAlert($observer->getData('request'))
        ) {
            $this->addAlertToPageContent($observer->getData('response'));
        }
    }

    private function isDisplayMaintenanceAlert(RequestHttp $request): bool
    {
        return $this->maintenanceMode->isOn() && !$request->isXmlHttpRequest();
    }


    private function addAlertToPageContent(Http $response): void
    {
        $replaceData = $this->replaceContent();

        $response->setContent(
            str_replace($replaceData['from'], $replaceData['to'], $this->alertRawContent())
            . $response->getContent()
        );
    }

    private function replaceContent(): array
    {
        return [
            'from' => [
                '%alert-message%', '%cookie-name%'
            ],
            'to' => [
                $this->maintenanceAlertMessage, static::DISABLE_MAINTENANCE_ALERT_MARKER_NAME
            ]
        ];
    }

    private function alertRawContent(): string
    {
        return
<<<RAWCONTENT
<style>
    body {
        --border-width: 4px;
    }
    #maintenance-frame .frame-border:after,
    #maintenance-frame .frame-border:before {
        content: '';
        position: fixed;
        background: red;
        z-index: 99999999999;
    }
    #maintenance-frame .frame-border:first-child:before {
        width: 100vw;
        height: var(--border-width);
        top: 0;
        left: 0;
    }
    #maintenance-frame .frame-border:first-child:after {
        width: var(--border-width);
        height: 100vh;
        top: 0;
        left: 0;
    }
    #maintenance-frame .frame-border:last-child:before {
        width: var(--border-width);
        height: 100vh;
        top: 0;
        right: 0;
    }
    #maintenance-frame .frame-border:last-child:after {
        width: 100vw;
        height: var(--border-width);
        bottom: 0;
        left: 0;
    }
    #maintenance-message {
        display: block;
        position: -webkit-sticky;
        position: sticky;
        z-index: 10000;
        top: 0;
        background: red;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        text-align: center;
        overflow: hidden;
    }

    #maintenance-message.hidden {
        display: none;
    }

    #maintenance-message__close {
        float: right;
        cursor: pointer;
    }

</style>
<div id="maintenance-frame">
    <div class="frame-border"></div><div class="frame-border"></div>
</div>

<div id="maintenance-message" class="hidden">
    %alert-message%
    <div id="maintenance-message__close" onclick="hideMaintenanceMessage()">x</div>
</div>

<script type="text/javascript">
    var cookieName = "%cookie-name%=true";

    if(!isCookieSet()) {
        document.getElementById('maintenance-message').classList.remove('hidden');
    }

    function isCookieSet() {
        return document.cookie.split(';').map(function(el){return el.trim()}).indexOf(cookieName) > -1;
    }

    function setMaintenanceCookie() {
        var date = new Date(),
            expires = 'expires=';

        date.setDate(date.getDate() + 1);
        expires += date.toString();
        document.cookie = cookieName + '; ' + expires + '; path=/';
    }

    function hideMaintenanceMessage() {
        document.getElementById('maintenance-message').classList.add('hidden');
        setMaintenanceCookie();
    }
</script>
RAWCONTENT;
    }
}
