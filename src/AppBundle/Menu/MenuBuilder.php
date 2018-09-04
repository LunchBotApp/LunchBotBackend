<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MenuBuilder
 *
 * @package AppBundle\Menu
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param FactoryInterface   $factory
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        $this->factory   = $factory;
        $this->container = $container;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav mr-auto');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            /**
             * Inbox
             */
            $inbox = $menu->addChild('Inbox')
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fas fa-inbox');

            $inbox->addChild('Crawler Errors', ['route' => 'crawler_report_list'])
                ->setAttribute('icon', 'fas fa-cloud-download-alt');
            $inbox->addChild('Restaurants', ['route' => 'feedback_report_restaurants'])
                ->setAttribute('icon', 'fas fa-utensils');
            $inbox->addChild('Issues', ['route' => 'issue_report_list'])
                ->setAttribute('icon', 'fas fa-exclamation-triangle');
            $inbox->addChild('Feedback', ['route' => 'feedback_report_list'])
                ->setAttribute('icon', 'fas fa-comment');
            $inbox->addChild('Food category', ['route' => 'meal_list_uncategorized'])
                ->setAttribute('icon', 'fas fa-boxes');

            /**
             * Food
             */
            $food = $menu->addChild('Food')
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fas fa-utensils');

            $food->addChild('List', ['route' => 'meal_list'])
                ->setAttribute('icon', 'fas fa-list-alt');
            $food->addChild('Meal', ['route' => 'meal_add'])
                ->setAttribute('icon', 'fas fa-plus-circle');
            $food->addChild('Categories', ['route' => 'category_list'])
                ->setAttribute('icon', 'fas fa-boxes');
            $food->addChild('Category', ['route' => 'category_add'])
                ->setAttribute('icon', 'fas fa-plus-circle');

            /**
             * Restaurants
             */
            $restaurants = $menu->addChild('Restaurants')
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fas fa-building');

            $restaurants->addChild('List', ['route' => 'restaurant_list'])
                ->setAttribute('icon', 'fas fa-list-alt');
            $restaurants->addChild('Add', ['route' => 'restaurant_add'])
                ->setAttribute('icon', 'fas fa-plus-circle');

            /**
             * Users
             */
            $users = $menu->addChild('Users')
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fas fa-users');

            $users->addChild('Admin Users', ['route' => 'admin_user_list'])
                ->setAttribute('icon', 'fas fa-users');
            $users->addChild('Add new Admin', ['route' => 'admin_user_add'])
                ->setAttribute('icon', 'fas fa-plus-circle');
            $users->addChild('Slack Users', ['route' => 'user_list'])
                ->setAttribute('icon', 'fas fa-user-secret');
        }

        return $menu;
    }


    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function loginMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav ml-1');

        /**
         * Logout
         */
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $logout = $menu->addChild('Logout', ['route' => 'fos_user_security_logout'])
                ->setAttribute('dropdown', false)
                ->setAttribute('icon', 'oi oi-account-logout');
        } else {
            $login = $menu->addChild('Login', ['route' => 'fos_user_security_login'])
                ->setAttribute('dropdown', false)
                ->setAttribute('icon', 'oi oi-account-login');
        }

        return $menu;
    }
}