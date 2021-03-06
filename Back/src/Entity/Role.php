<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *        "get",
 *        "post"={"access_control"="is_granted('ROLE_COMMUNITY_SUPERADMIN')", "access_control_message"="Désolé mais seuls les supers administrateurs peuvent ajouter un rôle !"}
 *     },
 *     itemOperations={
 *        "get",
 *        "put"={"access_control"="is_granted('ROLE_COMMUNITY_SUPERADMIN')", "access_control_message"="Désolé mais seuls les supers administrateurs peuvent modifier un rôle !"},
 *        "delete"={"access_control"="is_granted('ROLE_COMMUNITY_SUPERADMIN')", "access_control_message"="Désolé mais mais seuls les supers administrateurs peuvent supprimer un rôle !"}
 *     },
 *     iri="https://schema.org/Role",
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Le role ne doit pas dépasser {{ limit }} caractères"
     * )
     * @ApiProperty(iri="https://schema.org/roleName")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AppUser", mappedBy="role")
     */
    private $appUsers;

    public function __construct()
    {
        $this->appUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|AppUser[]
     */
    public function getAppUsers(): Collection
    {
        return $this->appUsers;
    }

    public function addAppUser(AppUser $appUser): self
    {
        if (!$this->appUsers->contains($appUser)) {
            $this->appUsers[] = $appUser;
            $appUser->setRole($this);
        }

        return $this;
    }

    public function removeAppUser(AppUser $appUser): self
    {
        if ($this->appUsers->contains($appUser)) {
            $this->appUsers->removeElement($appUser);
            // set the owning side to null (unless already changed)
            if ($appUser->getRole() === $this) {
                $appUser->setRole(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
