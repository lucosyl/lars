<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091100488946",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAznk6p+Z6Ms2AaBw7d0fBIyoAEszcIy4F8E5irYxVy34ugrLx/8C3e/mdWmswLaZcHcRex5GePfAmSNC/4Rwz5mLgNR3mGhpR59P2udaw4g1TTJ+ptZYyIhGdvlRNDEO1NPPE9hZIts1qimbCwgOjTZuC+8/sZhOxZH6dOjxDIuq7vnZGhXOfzHr3Ol6s1T4STqPxpXab2Cqm9dVu3kc4zHnQfKmG3UKMmldljbGcLPp/WrFMUJg/E75vM4VidF7q0QVQ0wBplcim+28xK6aPHIRVyjoFCbKjhngp8/Km3J/+WQnktEx6qkOlnpHOD/EM2MR8DD2faoE3To1LovG4XQIDAQABAoIBAQCe43m49Ur6R1xWQcudOhGtvsd5sD6DsAoP6DJVvVXUmBCwsYY7OxG4DNoDV9MmfgwLOC97wlqU7XZ4uHPAhtSFqXdCSPTnB3QaQ+ptDoLmIj0FDSUrQR7pSscqTXNRSdVrn1/lxDZGvoMLaJLdfLExqse8gkA4OVVcsXbK9s7vUXPJemkEQNTLOhvwxmWi1H/f9fIJHXeGHzY3Aft/Ck+NCGWcjVZR8nLiVjiqgz+AhpGOgaBNW1c7lT++RuANueqzL1eMcAPolXdVQla3K0D3Uy9JPyNA+pB/IwS4RMAKn3gmzOFxyOtF/5IX92i3LOCa7SKJuhkp7rWPvufieGhBAoGBAPGjlBNn7a48cbyezlVazBU8lH0FbcjZtIfx/l/N9rodI38VxkhmFFM0p2+p/olyN0nU5nEE7os6xXe+ddRP7ASBzB3HdCjI5rR4G/WGiNtGpqXrEom4ZxTWSVduhdTJmGWogNqFYZ9WsEuhpk0i6Gn131lUciKCV5q7G9YXniUxAoGBANq+oCM2u0rlRf+atuRkpJpvhsSXwL281Fx5fyOln0G9fpkht3qhY5bovXPeNSlw3sgeSn6h7AC25QVtzxz79RfGZLkEqCTrbvM0aNobNIipJn1TzfksHoABNtaQuh8/oqUwxWxR8LQ8fZR0xyfb1UXebKSs6zQdxXGPLAAfk2rtAoGAOuxFlnnYVo18up8K93tdmqwcFWR2gey8fg3/loMV8Apc9odrw4nI2QVU8wDEBeYpH0LgMp0eQitBrdJuzyfyCKNSD8zsQWnEACvtvXBh/P58cqcg7R5fXJBob/6OefXyyI9PZHnz9TPhZ3/ymoqxm+0N2q2h61JXZG7N+eXmdXECgYAp6kkpNj2hVlmZZv4QubBI34IFfcazFNHkfmt8WyovIK53jVxpJS++JQ7YewXY0QX/dr5zkXd1k5ZC+g2r0iPe0GdQhLHrkSmLdMl6safZYAA+F6bqTifyA0mMx+LCRiFWYggSf4kOIGE350CaRKeTYdGTpIbVT7cb0YFu7J4E6QKBgElXNBdNxlJGqbXTxiFwKB+pFhCxoaS6OQ+qt6jQ67MCl9IAfRWboY7K749jRM5jigyrzHN/cwRFk2F8cWVQfKKNPMdNV+rjO+TqB5n1vYsQ8nmwFxwmQIuuds7xT7KpzVa1TaeNAWi48Ejm4aNXAaUivswk8ibdETQIaRRd0JTH",
		
		//异步通知地址
		'notify_url' => "http://106.12.3.234/pay/public/ali_notify.html",
		
		//同步跳转
		'return_url' => "http://106.12.3.234/pay/public/ali_return.html",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv15l19Kv7KeqR0IUK4/Lvqq6XpMnD6MDGMI0HHX/huuNxqaRuMXMmlAoCWn1cO/IRhPqY6KDr4QqgSC/VCyNETK2hICkcSsmBg+b4YXbFX84lqF7NUM+ny65W4YZ1CD7UBsdtTSxwsOWHQuILACfa6Vhm/3UJ3zfOyQfSZvyMJlMwxlz4unUbVUcUL+u8AxIXqEWVarKJmh0AxDsLLypqZ1aFgsYYkXEgBAIoDj0mYKwcT/MX5MDep7bjhKAjkKF9STNPHeYEp1vcYGpovOzVCk+ptNzLopRsU4CB+K2c4LBemibddWycsalZ/+L29nM7NNxUvIgbBrM/pHpARGnIQIDAQAB",
);