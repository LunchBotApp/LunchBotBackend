<?php


namespace AppBundle\Controller;

use AppBundle\Entity\GroupSuggestion;
use AppBundle\Entity\Meal;
use AppBundle\Entity\MealChoice;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Rating;
use AppBundle\Entity\Settings;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\Suggestion;
use DateTime;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LunchController
 *
 * @package AppBundle\Controller
 */
class LunchController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Request a new lunch suggestion",
     * )
     * @Post("/api/v1/lunch/request/solo", defaults={"_format"="json"})
     * @View(serializerGroups={"suggestion", "user", "meal", "restaurant", "rating", "settings", "priceRange", "address", "language"})
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getLunchAction(Request $request)
    {
        $userCreated = 0;
        $userID      = $request->request->get('user');
        $groupID     = $request->request->get('group');
        $settings    = $request->request->get('settings');

        if (!$userID || !$groupID) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }


        $user = $this->get('app.usercreator')->checkAndcreateUser($userID)["user"];
        $this->get('app.usercreator')->userToGroup($userID, $groupID);

        $suggestion = new SoloSuggestion();
        $suggestion->setTimestamp(new DateTime());
        $suggestion->setUser($user);

        $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals();


        if ($settings) {
            $suggSettings = new Settings();
            if ($settings["pricerange"]) {
                $priceRangeA = explode("-", $settings["pricerange"]);
                $min         = $priceRangeA[0];
                $max         = $priceRangeA[1];

                $priceRange = new PriceRange();
                $priceRange->setMin($min);
                $priceRange->setMax($max);
                $suggSettings->setPriceRange($priceRange);
                $this->getDoctrine()->getManager()->persist($priceRange);

                $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMealsPrice($min, $max);
            }
            if ($settings["distance"]) {
                $suggSettings->setDistance($settings["distance"]);
            }
            $this->getDoctrine()->getManager()->persist($suggSettings);
            $suggestion->setSettings($suggSettings);
        }

        if ($user->getSettings() && $user->getSettings()->getPriceRange()) {
            $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMealsPrice($user->getSettings()->getPriceRange()->getMin(), $user->getSettings()->getPriceRange()->getMax());
        }
        if (count($meals) == 0) {

            $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals();
        }


        $suggestion->setSuggestions($this->getDoctrine()->getRepository(Suggestion::class)->getBestMatch($suggestion));

        $this->getDoctrine()->getManager()->persist($suggestion);
        $this->getDoctrine()->getManager()->flush();

        return ["settings" => $settings, "suggestion_id" => $suggestion->getId(), "meals" => $suggestion->getSuggestions()];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Request a new lunch suggestion",
     * )
     * @Post("/api/v1/lunch/request/group", defaults={"_format"="json"})
     * @View(serializerGroups={"suggestion", "user", "meal", "restaurant", "rating", "settings", "priceRange", "address", "language"})
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public
    function getLunchGroupAction(Request $request)
    {
        $userID   = $request->request->get('users');
        $groupID  = $request->request->get('group');
        $settings = $request->request->get('settings');

        if (!$userID || !$groupID) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $userIDs = explode(";", $userID);
        $users   = $this->get('app.usercreator')->userIdsToUsers($userIDs);

        $suggestion = new GroupSuggestion();
        $suggestion->setTimestamp(new DateTime());
        $suggestion->setUsers($users);
        $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals();

        if ($settings) {
            $suggSettings = new Settings();
            if ($settings["pricerange"]) {
                $priceRangeA = explode("-", $settings["pricerange"]);
                $min         = $priceRangeA[0];
                $max         = $priceRangeA[1];

                $priceRange = new PriceRange();
                $priceRange->setMin($min);
                $priceRange->setMax($max);
                $suggSettings->setPriceRange($priceRange);
                $this->getDoctrine()->getManager()->persist($priceRange);

                $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMealsPrice($min, $max);
            }
            if ($settings["distance"]) {
                $suggSettings->setDistance($settings["distance"]);
            }
            $this->getDoctrine()->getManager()->persist($suggSettings);
            $suggestion->setSettings($suggSettings);
        }


        $suggestion->setSuggestions($this->getDoctrine()->getRepository(Suggestion::class)->getBestMatch($suggestion));
        $this->getDoctrine()->getManager()->persist($suggestion);
        $this->getDoctrine()->getManager()->flush();

        return ["suggestion_id" => $suggestion->getId(), "meals" => $suggestion->getSuggestions()];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Sets a meal for a given suggestion, or creates suggestion",
     * )
     * @Post("/api/v1/lunch/meal/set", defaults={"_format"="json"})
     * @View(serializerGroups={"suggestion"})
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public
    function getMealSet(Request $request)
    {
        $userID       = $request->request->get('user');
        $mealID       = $request->request->get('meal');
        $suggestionID = $request->request->get('suggestion');

        if (!$userID || !$mealID || (!$suggestionID && !($suggestionID == 0))) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $user       = $this->get('app.usercreator')->checkAndcreateUser($userID)["user"];
        $meal       = $this->getDoctrine()->getRepository(Meal::class)->getById($mealID);
        $suggestion = $this->getDoctrine()->getRepository(Suggestion::class)->getById($suggestionID);

        if (!$suggestion) {
            $suggestion = new SoloSuggestion();
            $suggestion->setTimestamp(new DateTime());
            $suggestion->setUser($user);
            $this->getDoctrine()->getManager()->persist($suggestion);
            $this->getDoctrine()->getManager()->flush();
        }
        $mealChoice = new MealChoice();
        $mealChoice->setMeal($meal);
        $mealChoice->setUser($user);
        $mealChoice->setSuggestion($suggestion);
        $this->getDoctrine()->getManager()->persist($mealChoice);
        $this->getDoctrine()->getManager()->flush();

        return ["meal_choice" => $mealChoice->getId(), "suggestion_id" => $suggestion->getId(), "return_code" => 1, "message" => "MealChoice saved"];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Sets a meal for a given suggestion, or creates suggestion",
     * )
     * @Post("/api/v1/lunch/rating/set", defaults={"_format"="json"})
     * @View(serializerGroups={"suggestion"})
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public
    function getRatingSet(Request $request)
    {
        $value        = $request->request->get('value');
        $mealChoiceID = $request->request->get('meal_choice');

        if (!$value || !$mealChoiceID) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $mealChoice = $this->getDoctrine()->getRepository(MealChoice::class)->find($mealChoiceID);

        $rating = new Rating();
        $rating->setTimestamp(new DateTime());
        $rating->setUser($mealChoice->getUser());
        $rating->setMeal($mealChoice->getMeal());
        $rating->setSuggestion($mealChoice->getSuggestion());
        $rating->setValue($value);
        $this->getDoctrine()->getManager()->persist($rating);
        $this->getDoctrine()->getManager()->flush();


        return ["return_code" => 1, "message" => "Rating saved"];
    }
}