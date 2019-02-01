<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "O nome deve ter no mínimo 2 carateres",
     *      maxMessage = "O nome deve ter no maximo 100 carateres"
     * )
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Type(
     *     type="numeric",
     *     message="O telefone deve conter apenas números."
     * )
     * @Assert\Length(
     *      min = 7,
     *      max = 15,
     *      minMessage = "O telefone deve ter no mínimo 7 carateres",
     *      maxMessage = "O telefone deve ter no maximo 15 carateres"
     * )
     */
    private $telefone;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "{{ value }}' não é um email válido",
     *     checkMX = true
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "O email deve ter no maximo 100 carateres"
     * )
     */
    private $email;

    /**
     * @OneToMany(targetEntity="Address", mappedBy="contact")
     */
    private $addresses;

    public function __construct() {
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): self
    {
        $this->telefone = $telefone;

        return $this;
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

    public function getAddresses()
    {
        return $this->addresses;
    }
}
