<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idcontact;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "A quadra deve ter no mínimo 2 carateres",
     *      maxMessage = "A quadra deve ter no maximo 200 carateres"
     * )
     */
    private $quadra;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * @Assert\Type(
     *     type="numeric",
     *     message="O campo número deve conter apenas dígitos."
     * )
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "O campo número deve ter no maximo 15 digitos"
     * )
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "A observação deve conter no máximo 255 caracteres"
     * )
     */
    private $observacao;

    /**
     * @ManyToOne(targetEntity="Contact", inversedBy="Address")
     * @JoinColumn(name="idcontact", referencedColumnName="id")
     */
    private $contact;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdcontact(): ?int
    {
        return $this->idcontact;
    }

    public function setIdcontact(string $idcontact): self
    {
        $this->idcontact = $idcontact;

        return $this;
    }

    public function getQuadra(): ?string
    {
        return $this->quadra;
    }

    public function setQuadra(string $quadra): self
    {
        $this->quadra = $quadra;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function setContact($contact): self
    {
        $this->contact = $contact;
        return $this;
    }
}
