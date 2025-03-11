<?php

namespace core;

include_once 'Connect.php';

use core\ConnectModel;

class ProductBaseModel extends ConnectModel
{

    public function getALLProductsQuery()
    {
        $sql = "SELECT p.id as id_product, p.name, p.description, p.price, p.discount, p.status, i.image
                FROM product p
                inner join image_detail i on p.id = i.id_product
                WHERE is_main = 1";
        return $this->db->getAll($sql);;
    }

    public function getProductByDate()
    {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.discount, p.status, i.image
                FROM product p
                inner join image_detail i on p.id = i.id_product
                WHERE is_main = 1
                ORDER BY p.create_at ASC
                LIMIT 4;";
        return $this->db->getAll($sql);
    }

    public function getProductByDiscount()
    {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.discount, p.status, i.image
                FROM product p
                inner join image_detail i on p.id = i.id_product
                WHERE is_main = 1 AND p.discount > 0
                ORDER BY p.discount DESC
                LIMIT 4;";
        return $this->db->getAll($sql);
    }

    public function getProductSell()
    {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.discount, p.status, i.image,
                    SUM(od.quantity) AS total_sold
                FROM orders_detail od
                INNER JOIN orders o ON od.id_order = o.id
                INNER JOIN product p ON od.id_product = p.id
                INNER JOIN image_detail i ON p.id = i.id_product
                WHERE i.is_main = 1
                GROUP BY p.id, p.name, p.description, p.price, p.discount, p.status, i.image
                ORDER BY total_sold DESC
                LIMIT 8;";
        return $this->db->getAll($sql);
    }
    public function getDetailProductQuery($id)
    {
        $sql = "SELECT p.name, p.id AS product_id, p.price, p.discount, p.description, p.status, i.is_main, p.id_category, 
                        i.image as image, c.hex_code, p.id_colors
                    FROM product p
                    INNER JOIN image_detail i ON p.id = i.id_product  
                    INNER JOIN colors c ON c.id = p.id_colors  
                    WHERE p.id = :id;";
        return $this->db->getALL($sql, ['id' => $id]);
    }

    public function getColors()
    {
        $sql = "SELECT * FROM colors";
        return $this->db->getALL($sql);
    }

    public function getColorsById($id)
    {
        $sql = "SELECT * FROM colors WHERE id = :id";
        return $this->db->getALL($sql, [':id' => $id]);
    }

    public function getColorByHexOrName($name, $hex_code)
    {
        $sql = "SELECT * FROM colors WHERE name = :name OR hex_code = :hex_code";
        return $this->db->getOne($sql, [':name' => $name, ':hex_code' => $hex_code]);
    }

    public function insertColor($name, $hex_code)
    {
        $checkSql = "SELECT COUNT(*) as count FROM colors WHERE name = :name or hex_code = :hex_code";
        $checkParams = [':hex_code' => $hex_code, ':name' => $name];
        $count = $this->db->getOne($checkSql, $checkParams);

        if ($count['count'] > 0) {
            $_SESSION['error'] = "Màu này đã tồn tại! Vui lòng chọn màu khác.";
            header('location: /php2/ASMC/admin/color');
            exit;
        }

        $sql = "INSERT INTO colors (name, hex_code) VALUES (:name, :hex_code)";
        $params = [
            ':name' => $name,
            ':hex_code' => $hex_code
        ];

        return $this->db->insert($sql, $params);
    }


    public function deleteColor($id)
    {
        $checkSql = "SELECT COUNT(*) as count FROM product WHERE id_colors = :id";
        $checkParams = [':id' => $id];
        $count = $this->db->getOne($checkSql, $checkParams);

        if ($count['count'] > 0) {
            $_SESSION['error'] = "Không thể xóa màu này vì đang có sản phẩm sử dụng!";
            header('location: /php2/ASMC/admin/color');
            exit;
        }

        $sql = "DELETE FROM colors WHERE id = :id";
        $params = [':id' => $id];

        return $this->db->delete($sql, $params);
    }

    public function editColor($id, $name, $hex_code)
    {
        $sql = "UPDATE colors SET name = :name, hex_code = :hex_code WHERE id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':hex_code' => $hex_code
        ];

        return $this->db->update($sql, $params);
    }

    public function getSizes()
    {
        $sql = "SELECT type FROM size";
        return $this->db->getALL($sql);
    }

    public function getProductByCategoryQuery($categoryid)
    {
        $sql = "SELECT p.id as id_product, p.name, p.description, p.price, p.discount, i.image, p.status, ca.name as category_name, GROUP_CONCAT(i.image) as detail 
                FROM product p 
                INNER JOIN categories ca 
                ON ca.id = p.id_category 
                INNER JOIN image_detail i
                ON p.id = i.id_product
                WHERE (ca.id = :categoryid OR ca.id_parent = :categoryid)
                AND i.is_main = 1
                GROUP BY p.id;";
        return $this->db->getALL($sql, ['categoryid' => $categoryid]);
    }

    public function searchProductQuery($key)
    {
        $sql = "SELECT p.id as id_product, p.name, p.description, p.price, p.discount, i.image, p.status
                FROM product p
                inner join image_detail i on p.id = i.id_product
                WHERE name LIKE :key AND is_main = 1";
        return $this->db->getAll($sql, ['key' => '%' . $key . '%']);
    }

    public function getProductsAdmin()
    {
        $sql = "SELECT p.*, c.name as category_name, p.status,
                GROUP_CONCAT(i.image) as detail
                FROM product p
                INNER JOIN image_detail i
                ON p.id = i.id_product
                INNER JOIN categories c
                ON c.id = p.id_category
                GROUP BY p.id;";

        return $this->db->getALL($sql);
    }

    public function insertProduct($name, $price, $discount, $id_category, $description, $main_image, $thumbnails, $colorn)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO product (name, price, discount, id_category, description, id_colors) 
                    VALUES (:name, :price, :discount, :id_category, :description, :colorn);";

            $params = [
                ':name' => $name,
                ':price' => $price,
                ':discount' => $discount,
                ':id_category' => $id_category,
                ':description' => $description,
                ':colorn' => $colorn,
            ];

            $id_product = $this->db->insert($sql, $params);

            if (!empty($main_image)) {
                $sqlMainImage = "INSERT INTO image_detail (id_product, image, is_main) 
                                 VALUES (:id_product, :image, 1)";
                $this->db->insert($sqlMainImage, [
                    ':id_product' => $id_product,
                    ':image' => $main_image
                ]);
            }

            foreach ($thumbnails as $thumb) {
                $sqlThumb = "INSERT INTO image_detail (id_product, image, is_main) 
                             VALUES (:id_product, :image, 0)";
                $params = [
                    ':id_product' => $id_product,
                    ':image' => $thumb
                ];
                $this->db->insert($sqlThumb, $params);
            }


            $this->db->commit();
            return $id_product;
        } catch (\Exception $th) {
            $this->db->rollBack();
            echo "Lỗi: " . $th->getMessage();
            return false;
        }
    }

    public function deleteProduct($id)
    {
        try {
            $sqlImage = "DELETE FROM image_detail WHERE id_product = :id;";
            $this->db->delete($sqlImage, [':id' => $id]);
            $sql = "DELETE FROM product WHERE id = :id;";
            $this->db->delete($sql, [':id' => $id]);
            return true;
        } catch (\Exception $th) {
            echo "Lỗi: " . $th->getMessage();
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE product SET status = :status WHERE id = :id";
            $this->db->update($sql, [':id' => $id, ':status' => $status]);
            return true;
        } catch (\Exception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function checkProductOrder($id)
    {
        $sql = "SELECT COUNT(*) as count FROM orders_detail WHERE id_product = :id;";
        $check = $this->db->getOne($sql, [':id' => $id]);
        return $check['count'] > 0;
    }

    public function updateProduct($id_product, $name, $price, $discount, $id_category, $description, $colorn, $main_image = null, $thumbnails = [])
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE product 
                SET name = :name, price = :price, discount = :discount, 
                    id_category = :id_category, description = :description, id_colors = :colorn
                WHERE id = :id_product";

            $params = [
                ':id_product' => $id_product,
                ':name' => $name,
                ':price' => $price,
                ':discount' => $discount,
                ':id_category' => $id_category,
                ':description' => $description,
                ':colorn' => $colorn
            ];

            $this->db->update($sql, $params);

            if (!empty($main_image)) {
                $sqlDeleteMainImage = "DELETE FROM image_detail WHERE id_product = :id_product AND is_main = 1";
                $this->db->delete($sqlDeleteMainImage, [':id_product' => $id_product]);

                $sqlMainImage = "INSERT INTO image_detail (id_product, image, is_main) 
                             VALUES (:id_product, :image, 1)";
                $this->db->insert($sqlMainImage, [
                    ':id_product' => $id_product,
                    ':image' => $main_image
                ]);
            }

            if (!empty($thumbnails)) {
                $sqlDeleteThumbnails = "DELETE FROM image_detail WHERE id_product = :id_product AND is_main = 0";
                $this->db->delete($sqlDeleteThumbnails, [':id_product' => $id_product]);

                foreach ($thumbnails as $thumb) {
                    $sqlThumb = "INSERT INTO image_detail (id_product, image, is_main) 
                                 VALUES (:id_product, :image, 0)";
                    $params = [
                        ':id_product' => $id_product,
                        ':image' => $thumb
                    ];
                    $this->db->insert($sqlThumb, $params);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $th) {
            $this->db->rollBack();
            echo "Lỗi: " . $th->getMessage();
            return false;
        }
    }

    public function getProductByCategoryAdmin($categoryid)
    {
        $sql = "SELECT p.*, c.name as category_name, p.status,
                GROUP_CONCAT(i.image) as detail
                FROM product p
                INNER JOIN image_detail i
                ON p.id = i.id_product
                INNER JOIN categories c
                ON c.id = p.id_category
                WHERE (c.id = :categoryid OR c.id_parent = :categoryid) AND i.id_product = p.id
                GROUP BY p.id;";
        return $this->db->getALL($sql, ['categoryid' => $categoryid]);
    }

    public function searchProductAdmin($key)
    {
        $sql = "SELECT p.*, c.name as category_name, p.status,
                   GROUP_CONCAT(i.image) as detail
            FROM product p
            INNER JOIN image_detail i ON p.id = i.id_product
            INNER JOIN categories c ON c.id = p.id_category
            WHERE p.name LIKE :key
            GROUP BY p.id";
        return $this->db->getAll($sql, ['key' => '%' . $key . '%']);
    }

    public function getProductByStatus($status)
    {
        $sql = "SELECT p.*, c.name as category_name, p.status,
                GROUP_CONCAT(i.image) as detail
                FROM product p
                INNER JOIN image_detail i
                ON p.id = i.id_product
                INNER JOIN categories c
                ON c.id = p.id_category
                WHERE p.status = :status
                GROUP BY p.id;";
        return $this->db->getAll($sql, [':status' => $status]);
    }

    public function filterProducts($categoryId = null, $color = null, $minPrice = null, $maxPrice = null)
    {
        $sql = "SELECT p.id as id_product, p.name, p.description, p.price, p.discount, i.image, p.status
            FROM product p
            INNER JOIN image_detail i ON p.id = i.id_product
            INNER JOIN colors c ON p.id_colors = c.id
            INNER JOIN categories ca ON p.id_category = ca.id
            WHERE i.is_main = 1";

        $params = [];

        // Lọc theo danh mục
        if (!empty($categoryId) && is_array($categoryId)) {
            $placeholders = implode(',', array_fill(0, count($categoryId), '?'));
            $sql .= " AND p.id_category IN ($placeholders)";
            $params = array_merge($params, $categoryId);
        } elseif (!empty($categoryId)) {
            $sql .= " AND (p.id_category = ? OR ca.id_parent = ?)";
            $params[] = $categoryId;
        }

        // Lọc theo màu sắc
        if (!empty($color)) {
            $sql .= " AND p.id_colors = ?";
            $params[] = $color;
        }

        if (!empty($minPrice)) {
            $sql .= " AND p.price >= ?";
            $params[] = $minPrice;
        }

        if (!empty($maxPrice)) {
            $sql .= " AND p.price <= ?";
            $params[] = $maxPrice;
        }

        return $this->db->getALL($sql, $params);
    }
}
