<?php
class ProductController {
    public function create($data) {
        return Product::insert($data);
    }

    public function update($data, $id) {
        return Product::update($data, "id = $id");
    }

    public function delete($id) {
        return Product::delete("id = $id");
    }

    public function getById($id) {
        return Product::getBy("id = $id");
    }

    public function getAll() {
        return Product::getAll();
    }
}
?>
