## Inscription & CRM system for T-Both Performance company

This repository contains the core CRM for [T-Both Performance company](https://t-bothperformance.com/), the solution uses Yousign eSignature and Stripe Klarna to enroll leads into the company's online training programs and manage their enrollments

![admin](https://lh3.googleusercontent.com/pw/ABLVV84c4Bax_jz1JKflU2Ye2Q-JLj9LWYDHVvSUnN4GD_txBcXa7H534DyrcTfsLYZXucM9PGROnYtVuuhjmfP_aKOgjNK5wubiTso4BJemMhEWAN4hytKXgHRNMoGj8EdRrqxqxyiqARNdPeF4WSOsOxKcQziUvHa90Up6arDFTPPTziblPOTpbknXZsEurtyW-d9KUdPP7NG668A2cOU484qEO8WKD09wYPQshJ_PdjjvWNSVbeZv8n44KOFjdmBeJ6-8L1ETVD3e_WXD8sXBSRaTDkdSZoIslsXZwYOa95ytBwYsbZJuPZpbGpvB6CAH8bDonBJrQg7S28Feyk9IPa9kcDnE5sqvHiZXjl1DtQuVmJTsXypAsZPvvZ8jcWLjFmrcK8uPjwtX7M33I3pXJWJqMauC09M7b-UQG1fBl4Hgw7taag5W_Ej_HpKXVBCUZgAielYGxhKNKwrNbR7d3L69nI9omWxuKWBRpwiIP09F5YpuPPB0nsTtAAd-w59C540nqvYtXWkwNDsUa9potUdjeEgxyHO_g0y3UQ5gbrC5WEeGJdH6Wsx2iRHsZTUH03Ni7dQyvb2k4hoGx0yl7splW-adO4OGJTfA_uU6H74lOX20FIMGae2vQw6gMn9lbJm-M-kSjzQi_i2srAb6-EmlqyY4ret_COC4YvXwQuRnjOO5BBAtONtO3APiW8_nnpbiEJlE6rDIauKWeMYUDXhVj3EXuRorax5_bcxmZyyeUXIWtVDik3O3pDZWbAvJfumGPrYK65UXPdMQiON-HOxOilyFvW2tVfwYZWkO6gTWeSf8xRxnUaHlMwVrQW0iaQSEYmwYzvP0q-HyF-l_vA3RtXT8LhLLh-0M2QIErn5e0gNX7qZ10pyg_BCgbegJMkZjQ7rEE2MhsyPZugDvZ6XfgnhuVdNg49Cp9OX7p3q5QZRYWeH_Ec7MY_YkVFunzMkACrFuLeQnCrEIB1A0I5vssW0QVINOuPtkIUu8R0fpgfdRUQR335OfBYI2ZzmQR4DUzbq0lMjDuro829IxTHYBufYh9ys0yc7dybZ1_g=w1163-h573-no?authuser=0)
![lead](https://lh3.googleusercontent.com/pw/ABLVV843VINfRqJSzOeDtnzhyxUsV-LKajd86x7zlPTrH7NZsJtu5lbwwaJD_noD0gAIfmBXvTbJ3YPzBquXRA8V5bDmWSuqD0jqy_npZ-90ttRCYGElnz8QRK4KYfBAjq4HKryPq6JcoGcBKY571Sf-0ybG=w2168-h1640-s-no-gm?authuser=0)


-----

### How to use

- Clone the project with `git clone`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed` (it has sample testing data)
- Run `php artisan storage:link`
- Run `npm install && npm run dev`
- Launch the [inscription.t-bothperformance.test/lead](inscription.t-bothperformance.test/lead) for the lead app
- Launch the [inscription.t-bothperformance.test/admin](inscription.t-bothperformance.test/admin) for the admin app
