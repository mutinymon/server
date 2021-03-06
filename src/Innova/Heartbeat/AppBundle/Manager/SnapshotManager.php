<?php

namespace Innova\Heartbeat\AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Innova\Heartbeat\AppBundle\Document\Snapshot;
use Mmoreram\GearmanBundle\Service\GearmanClient;

/**
 * Manager for Snapshot Entity.
 */
class SnapshotManager
{
    protected $documentManager;
    protected $entityManager;
    protected $gearman;

    public function __construct(DocumentManager $documentManager, EntityManager $entityManager, GearmanClient $gearman)
    {
        $this->documentManager = $documentManager;
        $this->entityManager = $entityManager;
        $this->mongoDataRepo = $this->documentManager->getRepository('InnovaHeartbeatAppBundle:Snapshot');
        $this->dataRepo = $this->entityManager->getRepository('InnovaHeartbeatAppBundle:Server');
        $this->gearman = $gearman;
    }

    /**
     * get server connection.
     */
    public function getConnections()
    {
        $servers = $this->dataRepo->findAll();

        foreach ($servers as $server) {
            $this->gearman->doBackgroundJob(
                'InnovaHeartbeatAppBundleWorkersSshWorker~hasData',
                json_encode(
                    array(
                        'serverUid' => $server->getUid(),
                    )
                )
            );
        }
    }

    public function getRepository()
    {
        return $this->documentManager->getRepository('InnovaHeartbeatAppBundle:Snapshot');
    }
}
