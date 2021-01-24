<?php
    // --- Class - Product ---
    class Product {
        // Properties
        public $name;
        public $price;
        public $description;

        //Methods
        function getName() {
            return $this->name;
        }

        function setName($name) {
            $this->name = $name;
        }

        function getPrice() {
            return $this->price;
        }

        function setPrice($price) {
            $this->price = $price;
        }

        function getDescription() {
            return $this->description;
        }

        function setDescription($description) {
            $this->description = $description;
        }
    }
?>