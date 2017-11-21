<?php

use App\Core;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\Taxonomy;
use App\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__ . '/../bootstrap.php';

//header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Amsterdam');
setlocale(LC_TIME, 'nl_NL.utf8', 'nl_NL.utf-8', 'nld_NLD');
setlocale(LC_CTYPE, 'nl_NL.utf8', 'nl_NL.utf-8');

$em = Core::instance()->getEntityManager();

// create taxonomies
if (!count($taxonomies = $em->getRepository(Taxonomy::class)->findAll()))
{
	$taxonomies = [];

	foreach ([
		'Categorie' => 'nl',
		'Category' => 'en',
		'Boom' => 'nl',
		'Tree' => 'en'
	 ] as $name => $locale)
	{
		$taxonomy = new Taxonomy();
		$taxonomy->translate($locale)->setName($name);
		$taxonomy->mergeNewTranslations();

		$em->persist($taxonomy);

		$taxonomies[] = $taxonomy;
	}

	$em->flush();
}

//var_dump($taxonomies);

if (($user = $em->getRepository(User::class)->find(1)) === null)
{
	$user = (new User())
		->setName('John Doe')
		->setUsername('johndoe@softmedia.nl');

	$em->persist($user);
	$em->flush();
}

//var_dump($user);

if (($page = $em->getRepository(Page::class)->findOneBy(['slug' => 'home'])) === null)
{
	$page = (new Page())
		->setName('Home')
		->setPublished(true);

	$page->translate('nl')
		->setTitle('Welkom!')
		->setBody('<p>Dit is de homepagina.</p>');

	$page->translate('en')
		->setTitle('Welcome!')
		->setBody('<p>This is the home page.</p>');

	$page->mergeNewTranslations();

	foreach ([
		'Pagina' => 'nl',
		'Page' => 'en'
	 ] as $name => $locale)
	{
		$taxonomy = new Taxonomy();
		$taxonomy->translate($locale)->setName($name);
		$taxonomy->mergeNewTranslations();

		$page->addTaxonomy($taxonomy);
	}

	$page->setOwner($user);

	$em->persist($page);
	$em->flush();
}

//var_dump($page);

$menu = $em->getRepository(Menu::class)->find(1);

$encoder = new JsonEncoder();
$normalizer = (new ObjectNormalizer())->setCircularReferenceHandler(function($object)
{
	return (string)$object;
});

header('content-type: application/json; charset=utf-8');
$serializer = new Serializer([$normalizer], [$encoder]);
echo $serializer->serialize($menu, 'json');