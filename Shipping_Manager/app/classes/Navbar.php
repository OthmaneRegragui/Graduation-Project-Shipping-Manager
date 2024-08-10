<?php
class NavbarItem {
  public $text;
  public $direction;
  public $subItems;

  public function __construct($text, $direction, $subItems = []) {
      $this->text = $text;
      $this->direction = $direction;
      $this->subItems = $subItems;
  }
}

class Navbar {
  private $items;

  public function __construct($items) {
      $this->items = $items;
  }

  public function generate() {
      foreach ($this->items as $item) {
          $this->generateItem($item);
      }
  }

  private function generateItem($item) {
    if (!empty($item->subItems)) {
        echo '<li class="nav-item">';
        echo '<a class="nav-link" data-bs-toggle="collapse" href="#collapse-' . str_replace(' ', '', $item->text) . '" role="button" aria-expanded="false" aria-controls="collapse-' . str_replace(' ', '', $item->text) . '">';
        echo '<i class="fas fa-chevron-right arrow"></i>'; // Add arrow icon
        echo $item->text;
        echo '</a>';
        echo '<div class="collapse" id="collapse-' . str_replace(' ', '', $item->text) . '">';
        echo '<ul class="nav flex-column">';
        foreach ($item->subItems as $subItem) {
            echo '<li class="nav-item sub-item">'; 
            echo '<a class="nav-link" href="' . $subItem->direction . '">' . $subItem->text . '</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</li>';
    } else {
        echo '<li class="nav-item">'; // Add this line
        echo '<a class="nav-link" href="' . $item->direction . '">';
        echo $item->text;
        echo '</a>';
        echo '</li>'; // Add this line
    }
}

}
?>