<?php

namespace App\Tests\Controller;

use App\Entity\Flat;
use App\Tests\CreateEntityWebTestCase;

/**
 * @internal
 */
class AdminFlatControllerTest extends CreateEntityWebTestCase
{
    public function testEditFlat()
    {
        $flat = $this->em->getRepository(Flat::class)->findOneByNote('test');
        $crawler = $this->client->request('GET', sprintf('/admin/flat/%d/edit', $flat->getId()));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $buttonNode = $crawler->selectButton('admin_edit_flat_submit');
        $form = $buttonNode->form();
        $form['admin_edit_flat[guests]'] = 2;
        $crawler = $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->em->refresh($flat);
        $this->assertEquals(2, $flat->getGuests());
    }
}
