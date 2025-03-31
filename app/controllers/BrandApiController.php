<?php
require_once 'app/config/database.php';
require_once 'app/models/BrandsModel.php';

class BrandApiController
{
    private $brandModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->brandModel = new BrandsModel($this->db);
    }

    // Lấy danh sách 
    public function index()
    {
        header('Content-Type: application/json');
        $shoes = $this->brandModel->getBrands();

        echo json_encode($shoes);
    }

    // Lấy thông tin theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $shoe = $this->brandModel->getBrandById($id);

        if ($shoe) {
            echo json_encode($shoe);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Brand not found']);
        }
    }

    // Thêm mới
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        $name = $data['name'];
        
        $result = $this->brandModel->addBrand($name);
        
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Brand created successfully']);
        }
    }

    // Cập nhật theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'];
        

        $result = $this->brandModel->updateBrand($id, $name);
        
        if ($result) {
            echo json_encode(['message' => 'Brand updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Brand update failed']);
        }
    }

    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->brandModel->deleteBrand($id);
        
        if ($result) {
            echo json_encode(['message' => 'Brand deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Brand deletion failed']);
        }
    }
}
?>
