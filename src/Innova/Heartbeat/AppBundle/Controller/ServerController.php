<?php

namespace Innova\Heartbeat\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Innova\Heartbeat\AppBundle\Entity\Server;
use Symfony\Component\HttpFoundation\Request;
// use Innova\Heartbeat\AppBundle\Document\Server;
use Innova\Heartbeat\AppBundle\Document\ServerData;

class ServerController extends Controller {

    /**
     * Get and show the list of servers
     * @Route("servers", name="servers")
     * @Template()
     */
    public function serversAction() {
        $servers = $this->get('innova.server.manager')->getAll();
        // $this->getServersDataAction();
        return $this->render(
                    'servers.html.twig', array(
                    'title' => 'Servers',
                    'servers' => $servers
                        )
        );
    }

    /**
     * add a server
     * @Route("server/add", name="add_server")
     * @Template()
     */
    public function serverAddAction(Request $request) {

        $server = new Server();

        $form = $this->createFormBuilder($server)
                ->add('ip', 'text', array('required' => true))
                ->add('name', 'text', array('required' => true))
                ->add('os', 'text', array('required' => true))
                ->add('linuxDashUrl', 'text', array('required' => true))
                ->add('save', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // save server in database           
            $this->get('innova.server.manager')->save($server);

            // redirect to server list
            return $this->redirect($this->generateUrl('servers'));
        }

        return $this->render('serverAdd.html.twig', array(
                    'title' => 'Add a server',
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("server/{id}", name="server")
     * @Template()
     */
    public function serverDetailsAction($id) {
        $server = $this->get('innova.server.manager')->findOne($id);
        $serverData = $this->get('innova.serverdata.manager')->findByServerId($id);

        $details = json_decode($serverData->getDetails());
        return $this->render(
                'server.html.twig', array(
                'title' => 'Servers',
                'server' => $server,
                'data' => array('id' => $serverData->getId(), 'date' => $serverData->getDate()),
                'details' => $details
            )
        );
    }

    /**
     * @Route("serverDel/{id}", name="delete_server")
     * @Template()
     */
    public function deleteServerAction($id) {
        $server = $this->get('innova.server.manager')->findOne($id);
        if ($server) {
            $this->get('innova.server.manager')->delete($server);
        }
        return $this->redirect($this->generateUrl('servers'));
    }

    /**
     * Get all available servers data
     * this method is intended to be called from a scheduled script
     * @Route("serversData", name="servers_data")
     */
    public function getServersDataAction() {
        $servers = $this->get('innova.server.manager')->getAll();
        $results = array();
        foreach ($servers as $server) {
            // connect to server
            $connection = $this->get('innova.serverdata.manager')->getConnection($server->getIP(), 'heartbeat', '');

            if ($connection != null) {
                // get data
                $stream = ssh2_exec($connection, '/home/heartbeat/HeartbeatClient/client.sh', 0700);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $jsonResponse = stream_get_contents($stream_out);
                $result = json_decode($jsonResponse);
                
                $results[$server->getUid()] = $result;

                // save data in mongodb
                $serverData = new ServerData();
                $serverData->setServerId($server->getUid());
                $serverData->setDetails($jsonResponse);
                
                $this->get('innova.serverdata.manager')->save($serverData);
            }
        }
    }
}