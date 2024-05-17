<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employee;
use App\Exception\TooHighDateTimeRangeException;
use App\Request\AddEmployeeRequest;
use App\Request\RegisterWorkingTimeRequest;
use App\Services\WorkingTimeSaver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'add_employee', methods: ['POST'])]
    public function addEmployee(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $addEmployeeRequest = AddEmployeeRequest::fromArray($request->toArray());

        $errors = $validator->validate($addEmployeeRequest);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $employee = new Employee($addEmployeeRequest->getName(), $addEmployeeRequest->getSurname());

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json(['id' => (string)$employee->getId()], Response::HTTP_CREATED);
    }

    #[Route('/working_time', name: 'register_working_time', methods: ['POST'])]
    public function registerWorkingTime(
        Request $request,
        ValidatorInterface $validator,
        WorkingTimeSaver $workingTimeSaver
    ): JsonResponse {
        try {
            $registerEmploymentRequest = RegisterWorkingTimeRequest::fromArray($request->toArray());

            $errors = $validator->validate($registerEmploymentRequest);

            if (count($errors) > 0) {
                return new JsonResponse(['message' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            $workingTimeSaver->save($registerEmploymentRequest);
        } catch (\Throwable $e) {
            if ($e instanceof TooHighDateTimeRangeException) {
                return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            return $this->json(['message' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Working time has been added!']);
    }
}
