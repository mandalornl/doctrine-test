<?php

use App\Core;
use App\Entity\Page;
use App\Entity\Taxonomy;
use App\Entity\User;

require_once __DIR__ . '/../bootstrap.php';

//header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Amsterdam');
setlocale(LC_TIME, 'nl_NL.utf8', 'nl_NL.utf-8', 'nld_NLD');
setlocale(LC_CTYPE, 'nl_NL.utf8', 'nl_NL.utf-8');

$em = Core::instance()->getEntityManager();

$repo = $em->getRepository(Page::class);
if (!($page = $repo->findOneBy(['slug' => 'home'])))
{
	$page = (new Page())
		->setName('Home')
		->setPublished(true);

	$page->translate('nl')
		->setTitle('Welkom')
		->setBody('<p>Dit is de homepagina.</p>');

	$page->translate('en')
		->setTitle('Welcome')
		->setBody('<p>This is the home page.</p>');

	$page->mergeNewTranslations();

	$page->addTaxonomy((new Taxonomy())->setName('Foo'));
	$page->addTaxonomy((new Taxonomy())->setName('Bar'));
	$page->addTaxonomy((new Taxonomy())->setName('Lorum'));
	$page->addTaxonomy((new Taxonomy())->setName('Ipsum'));

	/**
	 * @var User $owner
	 */
	$owner = $em->getRepository(User::class)->find(1);
	if ($owner !== null)
	{
		$page->setOwner($owner);
	}

	$em->persist($page);
	$em->flush();
}

var_dump($page);