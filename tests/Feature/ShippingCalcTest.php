<?php

$save_path = './temp';
beforeAll(function () use ($save_path) {
    if (!is_dir($save_path) && !mkdir($save_path, 0777, true) && !is_dir($save_path)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $save_path));
    }
    $files = glob($save_path . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($save_path);
});
afterEach(function () use ($save_path) {
    $files = glob($save_path . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($save_path);
});

test('ShippingRequestDtoAddress', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDtoAddress": {
            "type": "object",
            "properties": {
              "address1": {
                "type": "string"
              },
              "address2": {
                "type": "string"
              }
            },
            "required": [
              "address1"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDtoAddress.php';
    validate_file($filename);
    expect(file_get_contents($filename))
        ->toContain('namespace Models\Generated;')
        ->toContain('use \Zerotoprod\ServiceModel\ServiceModel;')
        ->toContain('public ?string $address1 = null;')
        ->toContain('public readonly string $address2;');
});

test('ShippingRequestDtoAddress nullable', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDtoAddress": {
            "type": "object",
            "properties": {
              "address1": {
                "type": "string",
                "nullable": true
              },
              "address2": {
                "type": "string",
                "nullable": true
              }
            },
            "required": [
              "address1"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDtoAddress.php';
    validate_file($filename);
    expect(file_get_contents($filename))
        ->toContain('public ?string $address1 = null;')
        ->toContain('public readonly ?string $address2;');
});

test('ShippingRequestDtoPackage', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDtoPackage": {
            "type": "object",
            "properties": {
              "weight": {
                "type": "number",
                "minimum": 1
              },
              "length": {
                "type": "number",
                "minimum": 1
              },
              "requireInsurance": {
                "type": "boolean"
              }
            },
            "required": [
              "weight"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDtoPackage.php';
    validate_file($filename);
    expect(file_get_contents($filename))
        ->toContain('public float $weight = 1;')
        ->toContain('public readonly float $length;')
        ->toContain('public readonly bool $requireInsurance;');
});

test('ShippingRequestDtoPackage nullable', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDtoPackage": {
            "type": "object",
            "properties": {
              "weight": {
                "type": "number",
                "minimum": 1
              },
              "length": {
                "type": "number",
                "minimum": 1,
                "nullable": true
              },
              "requireInsurance": {
                "type": "boolean",
                "nullable": true
              }
            },
            "required": [
              "weight"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDtoPackage.php';
    validate_file($filename);
    expect(file_get_contents($filename))
        ->toContain('public float $weight = 1;')
        ->toContain('public readonly ?float $length;')
        ->toContain('public readonly ?bool $requireInsurance;');
});

test('ShippingRequestDto', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDto": {
            "type": "object",
            "properties": {
              "originAddress": {
                "$ref": "#/components/schemas/ShippingRequestDtoAddress"
              },
              "destinationAddress": {
                "$ref": "#/components/schemas/ShippingRequestDtoAddress"
              },
              "packages": {
                "type": "array",
                "items": {
                  "$ref": "#/components/schemas/ShippingRequestDtoPackage"
                }
              }
            },
            "required": [
              "originAddress"
            ]
          },
          "ShippingRequestDtoAddress": {
            "type": "object",
            "properties": {
              "address1": {
                "type": "string"
              }
            }
          },
          "ShippingRequestDtoPackage": {
            "type": "object",
            "properties": {
              "weight": {
                "type": "number",
                "minimum": 1
              }
            },
            "required": [
              "weight"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDto.php';
    validate_file($filename);
    expect(file_get_contents($save_path . '/ShippingRequestDtoAddress.php'))
        ->and(file_get_contents($save_path . '/ShippingRequestDtoPackage.php'))
        ->and(file_get_contents($filename))
        ->toContain('public ?ShippingRequestDtoAddress $originAddress = null;')
        ->toContain('public readonly ShippingRequestDtoAddress $destinationAddress;')
        ->toContain('@var ShippingRequestDtoPackage[] $packages')
        ->toContain('#[CastToArray(ShippingRequestDtoPackage::class)]')
        ->toContain('public readonly array $packages;');
});

test('ShippingRequestDto nullable', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingRequestDto": {
            "type": "object",
            "properties": {
              "originAddress": {
                "$ref": "#/components/schemas/ShippingRequestDtoAddress"
              },
              "destinationAddress": {
                "$ref": "#/components/schemas/ShippingRequestDtoAddress"
              },
              "packages": {
                "type": "array",
                "nullable": true,
                "items": {
                  "$ref": "#/components/schemas/ShippingRequestDtoPackage"
                }
              }
            },
            "required": [
              "originAddress",
              "packages"
            ]
          },
          "ShippingRequestDtoAddress": {
            "type": "object",
            "properties": {
              "address1": {
                "type": "string"
              }
            }
          },
          "ShippingRequestDtoPackage": {
            "type": "object",
            "properties": {
              "weight": {
                "type": "number",
                "minimum": 1
              }
            },
            "required": [
              "weight",
              "packages"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    $filename = $save_path . '/ShippingRequestDto.php';
    validate_file($filename);
    expect(file_get_contents($filename))
        ->toContain('@var ShippingRequestDtoPackage[]|null $packages')
        ->toContain('#[CastToArray(ShippingRequestDtoPackage::class)]')
        ->toContain('public ?array $packages = [];');
});

test('RatesResponse', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "RatesResponse": {
            "type": "object",
            "properties": {}
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    expect(file_exists($save_path . '/RatesResponse.php'))->toBeFalse();
});

test('ReturnRequest', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ReturnRequest": {
            "type": "object",
            "properties": {
              "destinationCampus": {
                "type": "string",
                "enum": [
                  "FWA",
                  "PHX"
                ]
              }
            }
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    validate_file($save_path . '/ReturnRequest.php');
    validate_file($save_path . '/DestinationCampus.php');
    expect(file_get_contents($save_path . '/DestinationCampus.php'))
        ->toContain('namespace Models\Generated;')
        ->toContain('enum DestinationCampus: string')
        ->toContain('case FWA = \'FWA\';')
        ->toContain('case PHX = \'PHX\';')
        ->and(file_get_contents($save_path . '/ReturnRequest.php'))
        ->toContain('use \Zerotoprod\ServiceModel\ServiceModel;')
        ->toContain('namespace Models\Generated;')
        ->toContain('public readonly DestinationCampus $destinationCampus;');
});


test('ShipmentPreferences', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShipmentPreferences": {
            "type": "object",
            "properties": {
              "disallowedCarriers": {
                "type": "array",
                "items": {
                  "type": "string",
                  "enum": [
                    "DHL",
                    "UPS",
                    "USPS",
                    "FedEx"
                  ]
                }
              }
            },
            "required": [
              "disallowedCarriers"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    validate_file($save_path . '/ShipmentPreferences.php');
    validate_file($save_path . '/DisallowedCarriers.php');
    expect(file_get_contents($save_path . '/DisallowedCarriers.php'))
        ->toContain('namespace Models\Generated;')
        ->toContain('enum DisallowedCarriers: string')
        ->toContain('case DHL = \'DHL\';')
        ->toContain('case UPS = \'UPS\';')
        ->and(file_get_contents($save_path . '/ShipmentPreferences.php'))
        ->toContain('use \Zerotoprod\ServiceModel\ServiceModel;')
        ->toContain('namespace Models\Generated;')
        ->toContain('public readonly DisallowedCarriers $disallowedCarriers;');
});

test('ShippingOptionADSInfo', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingOptionADSInfo": {
            "type": "object",
            "properties": {
              "exclusions": {
                "type": "array",
                "items": {
                  "format": "date-time",
                  "type": "string"
                }
              }
            }
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    validate_file($save_path . '/ShippingOptionADSInfo.php');
    expect(file_get_contents($save_path . '/ShippingOptionADSInfo.php'))
        ->toContain('use \Zerotoprod\ServiceModel\ServiceModel;')
        ->toContain('namespace Models\Generated;')
        ->toContain('class ShippingOptionADSInfo')
        ->toContain('public readonly array $exclusions;');
});

test('ShippingOptionADSInfo nullable', function () use ($save_path) {
    $json = <<<'JSON'
    {
      "components": {
        "schemas": {
          "ShippingOptionADSInfo": {
            "type": "object",
            "properties": {
              "exclusions": {
                "nullable": true,
                "type": "array",
                "items": {
                  "format": "date-time",
                  "type": "string"
                }
              }
            },
            "required": [
              "exclusions"
            ]
          }
        }
      }
    }
    JSON;

    generate($json, $save_path, 'Models\\Generated');

    validate_file($save_path . '/ShippingOptionADSInfo.php');
    expect(file_get_contents($save_path . '/ShippingOptionADSInfo.php'))
        ->toContain('use \Zerotoprod\ServiceModel\ServiceModel;')
        ->toContain('namespace Models\Generated;')
        ->toContain('class ShippingOptionADSInfo')
        ->toContain('public ?array $exclusions = null;');
});