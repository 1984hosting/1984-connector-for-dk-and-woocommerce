<?php

namespace NineteenEightyFour\NineteenEightyWoo\Service;

/**
 * Communications between DK API and classes that implement WooCommerce or other
 * implementations in PHP.
 *
 * Each function represents an endpoint in DK. If there are parameters or optional parameters
 * they are added to the connection string for the API.
 *
 * Finally, every function calls the apiQuery function that cURLs the request sent to DK.
 */
class DkApiService implements DkApiServiceInterface {
  /**
   * Constructs a new DKApiService object.
   */
  public function __construct() {

  }

  /**
   * Fetches all products with given parameters. First two are mandatory. In order to not get too big
   * of a dataset, it's better to fetch each product group by GroupID.
   *
   * @param string|null $inactive
   * @param string|null $onweb
   * @param string|null $group
   * @param string|null $warehouse
   * @param string|null $modified
   * @param string|null $modified_before
   * @return array
   */
  public function getProducts(
    string $inactive = null,
    string $onweb = null,
    string $group = null,
    string $warehouse = null,
    string $modified = null,
    string $modified_before = null
  ) : array {
    $connectionString = "/Product?";
    $connectionString .= isset($onweb) ? "onweb=" . $onweb : '';
    $connectionString .= isset($inactive) ? '&inactive=' . $inactive : '';
    $connectionString .= isset($group) ? '&group=' . $group : '';
    $connectionString .= isset($warehouse) ? '&warehouse=' . $warehouse : '';
    $connectionString .= isset($modified) ? '&modified=' . $modified : '';
    $connectionString .= isset($modified_before) ? '&modified_before=' . $modified_before : '';

    return $this->apiQuery(trim($connectionString));
  }

  public function getProductGroups() : array {
    $connectionString = '/productgroup';

    return $this->apiQuery(trim($connectionString));
  }

  public function getProductById(string $id) : array {
    $id = base64_encode($id);
    $connectionString = '/Product/' . $id;
    $connectionString .= '?isBase64=true';

    return $this->apiQuery($connectionString);
  }

  public function getCustomers(bool $include_objects) : array {
    $connectionString = '/Customer?includeObjects=false';

    return $this->apiQuery($connectionString);
  }
  /**
   * Gets one customer by ID. If no customer is found, NULL is returned.
   *
   * @param string $id The Social Security Number of the customer
   *
   * @return array|null
   */
  public function getCustomerById(string $id) : array|null {
    $connectionString = '/Customer/' . $id;

    return $this->apiQuery($connectionString);
  }

  /**
   * Updates one customer with data.
   *
   * @param string $id
   * @param string $data JSON formatted string with data.
   * @return array
   */
  public function updateCustomer(string $id, string $data) : array {
    $connection_string = '/Customer/' . $id;

    return $this->apiQuery($connection_string, 'PUT', $data);
  }

  /**
   * Creates a customer, by the user data.
   *
   * @param string $data
   *
   * @return array
   */
  public function createCustomer(string $data) : array {
    $connection_string = '/Customer';
    return $this->apiQuery($connection_string, 'POST', $data);
  }

  public function getOrdersForCustomer(string $id) : array {
    $connection_string = '/Customer/' . $id . '/order';

    return $this->apiQuery($connection_string);
  }

  public function getInvoicesForCustomer(string $id) : array {
    $connection_string = '/Customer/' . $id . '/invoice';

    return $this->apiQuery($connection_string);
  }

  /**
   * Creates a product, by the user data.
   *
   * @param string $data
   *
   * @return array
   */
  public function createProduct(string $data) : array {
    $connection_string = '/Product';
    return $this->apiQuery($connection_string, 'POST', $data);
  }

  /**
   * Creates a Sales Order within DK
   *
   * @param string $data JSON String with the post data
   *
   * @return array
   */
  public function createSalesOrder(string $data) : array {
    $connectionString = '/sales/order';
    return $this->apiQuery($connectionString, 'POST', $data);
  }

  /**
   * Gets all Receivers for one customer id
   *
   * @param string $customer_number
   * @return array of Receivers
   * @return array
   */
  public function getReciversAssignedToCustomer(string $customer_number): array
  {
    $connectionString = '/Customer/' . $customer_number . '/Reciver';
    return $this->apiQuery($connectionString);
  }

  /**
   * Gets a contact (receiver) assigned to a customer.
   *
   * @param string $customer_number
   * @param string $contact_number
   * @return array
   */
  public function getContactAssignedToCustomer(string $customer_number, string $contact_number) : array
  {
    $connectionString = '/Customer/' . $customer_number . '/Reciver/' . $contact_number;
    return $this->apiQuery($connectionString);
  }

  /**
   * Deletes an contact (receiver) that is assigned to a Customer.
   *
   * @param string $customer_number
   * @param string $contact_number
   * @return array
   */
  public function deleteContactAssignedToCustomer(string $customer_number, string $contact_number) : array {
    $connectionString = 'Customer/' . $customer_number . '/reciver/' . $contact_number;
    return $this->apiQuery($connectionString, 'DELETE');
  }

  /**
   * Updates a contact that is assigned to a customer.
   *
   * @param string $customer_number
   * @param string $contact_number
   * @param string $data
   * @return array
   */
  public function updateContactAssignedToCustomer(string $customer_number, string $contact_number, string $data) : array {
    $connectionString = '/Customer/' . $customer_number . '/reciver/' . $contact_number;
    return $this->apiQuery($connectionString, 'PUT', $data);
  }

  /**
   * Gets all customer groups.
   *
   * @param string|null $modifiedAfter
   * @return array
   */
  public function getCustomerGroups(?string $modifiedAfter = null) : array
  {
    $connectionString = '/customergroup';
    if(is_string($modifiedAfter)) {
      $connectionString .= '?modifiedAfter=' . $modifiedAfter;
    }
    return $this->apiQuery($connectionString);
  }

  /**
   * Gets one receiver (contact) by it's number.
   *
   * @param string $number
   * @return array
   */
  public function getItemReciverByNumber(string $number) : array
  {
    $connectionString = '/Reciver/' . $number;

    return $this->apiQuery($connectionString);
  }

  /**
   * Creates a Customer receiver (person who receives the product).
   *
   * @param string $customer_number
   * @param string $data
   * @return array
   */
  public function createReciver(string $customer_number, string $data) : array {
    $connectionString = '/Customer/' . $customer_number . '/reciver';
    return $this->apiQuery($connectionString, 'POST', $data);
  }

  /**
   * Gets Receiver Customer by receiver number.
   *
   * @param string $number
   * @return array
   */
  public function getReciverCustomerByReciverNumber(string $number) : array {
    $connectionString = '/Reciver/' . $number . '/customer';
    return $this->apiQuery($connectionString);
  }

  /**
   * Creates an Invoice within DK
   *
   * @param string $data JSON String with the post data
   * @param bool $print_invoice Indicates if the invoice should be printed or filed
   *             as "unprinted".
   *
   * @return array
   */
  public function createInvoice(string $data, bool $print_invoice = FALSE) : array {
    $connectionString = '/sales/invoice';
    $connectionString .= ($print_invoice)
      ? '?post=True'
      : '?post=False';

    return $this->apiQuery($connectionString, 'POST', $data);
  }

  public function deleteProduct($sku): array
  {
    $base64_encoded_sku = base64_encode($sku);
    $connection_string = '/Product/' . $base64_encoded_sku;
    $connection_string .= '?isBase64=true';
    return $this->apiQuery($connection_string, 'DELETE');
  }

  public function getPDFVersionOfInvoice(string $invoice_number) : array
  {
    $connection_string = '/sales/invoice/' . $invoice_number . '/pdf';
    return $this->apiQuery($connection_string, 'GET', null, false);
  }

  public function getHTMLVersionOfInvoice(string $invoice_number) : array
  {
    $connection_string = '/sales/invoice/' . $invoice_number . '/html';
    return $this->apiQuery($connection_string, 'GET', null, false);
  }

  public function sendInvoiceAsEmail(string $invoice_number, $email_message) : array {
    $connection_string = '/sales/invoice/' . $invoice_number . '/email';
    return $this->apiQuery($connection_string, 'POST', $email_message);
  }

  public function getSaleInvoices(int $page, int $count, string $sorttype = 'Ascending', bool $includeLines = null, string $createdAfter = null,
                                  string $createdBefore = null, string $modifiedAfter = null, string $modifiedBefore = null,
                                  string $dueAfter = null, string $salesPerson = null, string $reference = null,
                                  int $recordid = null, string $customer = null, string $project = null,
                                  string $itemcode = null, string $include = null, string $exclude = null,
                                  string $sort = null) : array
  {
    $connection_string = "/sales/invoice/page/" . $page . '/' . $count . '?';
    $connection_string .= isset($includeLines) ? "&includeLines=" . $includeLines : '';
    $connection_string .= isset($createdAfter) ? '&createdAfter=' . $createdAfter : '';
    $connection_string .= isset($createdBefore) ? '&createdBefore=' . $createdBefore : '';
    $connection_string .= isset($modifiedAfter) ? '&modifiedAfter=' . $modifiedAfter : '';
    $connection_string .= isset($modifiedBefore) ? '&modifiedBefore=' . $modifiedBefore : '';
    $connection_string .= isset($dueAfter) ? '&dueAfter=' . $dueAfter : '';
    $connection_string .= isset($salesPerson) ? '&salesPerson=' . $salesPerson : '';
    $connection_string .= isset($reference) ? '&reference=' . $reference : '';
    $connection_string .= isset($recordid) ? '&recordid=' . $recordid : '';
    $connection_string .= isset($customer) ? '&customer=' . $customer : '';
    $connection_string .= isset($project) ? '&project=' . $project : '';
    $connection_string .= isset($itemcode) ? '&itemcode=' . $itemcode : '';
    $connection_string .= isset($include) ? '&include=' . $include : '';
    $connection_string .= isset($exclude) ? '&exclude=' . $exclude : '';
    $connection_string .= isset($sort) ? '&sort=' . $sort : '';
    $connection_string .= '&sorttype=' . $sorttype;
    return $this->apiQuery($connection_string);
  }

  public function getSalesInvoice(string $invoice_number) : array
  {
    $connection_string = '/sales/invoice/' . $invoice_number;
    return $this->apiQuery($connection_string);
  }

  public function getSalesInvoiceBasedOnExternalDKPosInvoiceNumber(int $externalInvoiceNumber) : array
  {
    $connection_string = '/sales/invoice/pos/' . $externalInvoiceNumber;
    return $this->apiQuery($connection_string);
  }

  public function getInvoiceByReferenceNumber(string $reference, int $page = 1, int $count = 100, string $type = null) : array
  {
    $connection_string = '/sales/invoice/reference/' . $reference . '/' . $page . '/' . $count;
    $connection_string .= isset($type) ? '?type=' . $type : '';
    return $this->apiQuery($connection_string);
  }

  public function getCompanyRelatedInformationAndSettings() : array
  {
    $connection_string = '/company';
    return $this->apiQuery($connection_string);
  }

  /**
   *********************** CONNECTING TO THE API ***********************
   */

  /**
   * apiQuery function
   *
   * @param string $path The Path to the API call (e.g. /Product or /barcode). If the request
   *   is GET, then the parameters must be included within the path.
   * @param string $method
   * @param string|null $data If post request, data needs to follow in order to send the request.
   * @param bool $encode_response If false, the response is not json encoded, but returned raw.
   *                              Used for responses like HTML or file streams.
   *
   * @return array with response code and json converted result
   */
  private function apiQuery(string $path, string $method = 'GET', string $data = null, bool $encode_response = true) : array
  {
    $url = 'https://api.dkplus.is/api/v1';
    $key = '3541031f-baf2-4737-a7e8-c66396e5a5e3';

    $headers = array(
      "Authorization: bearer " . $key
    );
    if($method == 'GET') {
      $headers[] = "Accept: application/json";
    }
    else if($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
      $headers[] = "Content-Type: application/json";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    if($method == 'POST') {
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      $headers[] = 'Content-Length: ' . strlen($data);
    }
    else if($method == 'PUT') {
      $putData = tmpfile();
      fwrite($putData, $data);
      fseek($putData, 0);
      curl_setopt($ch, CURLOPT_PUT, TRUE);
      curl_setopt($ch, CURLOPT_INFILE, $putData);
      curl_setopt($ch, CURLOPT_INFILESIZE, strlen($data));
    }
    else if($method == 'DELETE') {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
      if(isset($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);

    if ($curl_error) {
      echo "cURL Error #:" . $curl_error;
    }

    curl_close($ch);

    if($encode_response) {
      $json_data = json_decode($response);
    }

    return [
      'data' => ($encode_response) ? $json_data : $response,
      'response_code' => $httpCode
    ];
  }
}
