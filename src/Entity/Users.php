<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true, nullable: false)]
    private ?string $login = null;
	
	#[ORM\Column(length: 40, unique: true, nullable: false)]
    private ?string $email = null;
	
	#[ORM\Column(length: 60, nullable: false)]
    private ?string $lastname = null;
	
	#[ORM\Column(length: 60, nullable: false)]
    private ?string $firstname = null;
	
	#[ORM\Column(length: 60, nullable: true)]
    private ?string $middlename = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int{
        return $this->id;
    }

    public function getLogin(): ?string{
        return $this->login;
    }

	public function getEmail(): ?string{
        return $this->email;
    }	
	public function getFirstname(): ?string{
        return $this->firstname;
    }
	public function getLastname(): ?string{
        return $this->lastname;
    }
	public function getMiddlename(): ?string{
        return $this->middlename;
    }
	
	
		
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string{
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array{
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_NEWBERS';

        return array_unique($roles);
    }
	
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string{
        return $this->password;
    }	
	
	
	public function setRoles(array $roles): static{
        $this->roles = $roles;
        return $this;
    }	
	public function setPassword(string $password): static{
        $this->password = $password;
        return $this;
    }
	public function setLogin(string $login): static {
        $this->login = $login;
        return $this;
    }	
	public function setEmail(string $email): static{
		$this->email = $email;
        return $this;
    }	
	public function setFirstname(string $firstname): static{
        $this->firstname = $firstname;
		return $this;
    }
	public function setLastname(string $lastname): static{
        $this->lastname = $lastname;
		return $this;
    }
	public function setMiddlename(?string $middlename): static{
        $this->middlename = $middlename;
		return $this;
    }
	
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void{
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
