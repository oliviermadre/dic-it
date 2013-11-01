<?php
class CountryControllerTest extends AbstractController {
    public function getAction() {
        $serviceCountry = $this->container->get('CountryService');
        $serviceCountry->save();
    }
}