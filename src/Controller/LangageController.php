<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\LocaleSwitcher;

class LangageController extends AbstractController
{

    public function __construct(
        private LocaleSwitcher $localeSwitcher,
    ) {}
    
    #[Route('/change-language/{lang}', name: 'change_language')]
    public function changeLanguage(string $lang, Request $request, SessionInterface $session): RedirectResponse
    {
        $session->set('_locale', $lang); 
        $this->localeSwitcher->setLocale('en');
        dump($session->get('_locale'));
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: '/'); 
    }
}
