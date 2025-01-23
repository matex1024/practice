<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['id', 'user', 'user:read']],
    denormalizationContext: ['groups' => ['user', 'user:write']],
    security: "is_granted('ROLE_USER')"
)]
#[ApiFilter(SearchFilter::class, strategy: 'exact', properties: ['email'])]
#[Get(security: "is_granted('USER_READ', object)")]
#[Post(securityPostDenormalize: "is_granted('USER_CREATE', object)")]
#[Put(security: "is_granted('USER_UPDATE', object)")]
#[Patch(security: "is_granted('USER_UPDATE', object)")]
#[Delete(security: "is_granted('USER_DELETE', object)")]
#[GetCollection] // Scoped by UserScopedExtension
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimestampTrait;

    #[Groups(groups: ['user'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[Groups(groups: ['user'])]
    #[ORM\Column(type: 'string', length: 180)]
    private string $firstname;

    #[Groups(groups: ['user'])]
    #[ORM\Column(type: 'string', length: 180)]
    private string $lastname;

    #[Groups(groups: ['user'])]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $enabled = false;

    public function __toString()
    {
        return (string) $this->getDisplayName();
    }

    public function getUserIdentifier(): string
    {
        return $this->id->__toString();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->getEmail();
    }

    public function getDisplayName(): mixed
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
