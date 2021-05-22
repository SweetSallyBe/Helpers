# Helpers

various Symfony helpers for my projects

This is a starter for some of my helpers. I know there might be better solutions than this one. But if you can use
these, please be my guest. The main goal is to keep these as simple as possible. If you want to help using these, please
be my guest.

## Use

- Extend your classes from SweetSallyBe\Helpers\Entity\AbstractEntity to get default properties for id, createdAt and
  updatedAt.
- Extend your repositories form SweetSallyBe\Helpers\Entity\Repository\AbstractRepository to get default save and delete
  method
- Implement and use SweetSallyBe\Helpers\Entity\Interfaces\Token and SweetSallyBe\Helpers\Entity\Traits to have methods
  to get and set tokens
- Use EasyAminSubscriber to set createdAt and updatedAt
- Use static SweetSallyBe\Helpers\Service for various extra's